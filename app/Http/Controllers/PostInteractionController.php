<?php

namespace App\Http\Controllers;

use App\Models\CommentReaction;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostReaction;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostInteractionController extends Controller
{
    public function comment(Request $request, Post $post)
    {
        abort_unless($post->PostStatus === 'Đã duyệt', 404);

        $validated = $request->validate([
            'Content' => ['nullable', 'string', 'max:500', 'required_without:image'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'ParentCommentID' => ['nullable', 'exists:post_comments,CommentID'],
        ]);
        if (! empty($validated['ParentCommentID'])) {
            abort_unless(
                PostComment::where('CommentID', $validated['ParentCommentID'])
                    ->where('PostID', $post->PostID)
                    ->whereNull('ParentCommentID')
                    ->where('Status', 'Hiển thị')
                    ->exists(),
                404
            );
        }
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('posts/comments', 'public')
            : null;

        $max = PostComment::selectRaw('MAX(CAST(SUBSTRING(CommentID, 3) AS UNSIGNED)) AS m')->value('m');
        $post->comments()->create([
            'CommentID' => 'CM'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT),
            'UserID' => auth()->user()->UserID,
            'ParentCommentID' => $validated['ParentCommentID'] ?? null,
            'Content' => $validated['Content'] ?? '',
            'ImagePath' => $imagePath,
            'Status' => 'Hiển thị',
        ]);

        return back()->with('success', 'Đã đăng bình luận.');
    }

    public function updateComment(Request $request, PostComment $comment)
    {
        abort_unless(
            $comment->UserID === auth()->user()->UserID || auth()->user()->isAdmin(),
            403
        );
        $validated = $request->validate([
            'Content' => ['required', 'string', 'max:500'],
        ]);
        $comment->update(['Content' => $validated['Content'], 'UpdatedAt' => now()]);

        return back()->with('success', 'Đã cập nhật bình luận.');
    }

    public function destroyComment(PostComment $comment)
    {
        abort_unless(
            $comment->UserID === auth()->user()->UserID || auth()->user()->isAdmin(),
            403
        );
        if ($comment->ImagePath) {
            Storage::disk('public')->delete($comment->ImagePath);
        }
        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận.');
    }

    public function report(Request $request, Post $post)
    {
        abort_unless($post->PostStatus === 'Đã duyệt', 404);
        $validated = $request->validate([
            'Reason' => ['required', 'string', 'max:500'],
        ]);

        $existing = $post->reports()->where('UserID', auth()->user()->UserID)->first();
        if ($existing) {
            return back()->with('error', 'Bạn đã báo cáo bài đăng này.');
        }

        $max = PostReport::selectRaw('MAX(CAST(SUBSTRING(ReportID, 3) AS UNSIGNED)) AS m')->value('m');
        $post->reports()->create([
            'ReportID' => 'RP'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT),
            'UserID' => auth()->user()->UserID,
            'Reason' => $validated['Reason'],
            'Status' => 'Chờ xử lý',
        ]);

        return back()->with('success', 'Đã gửi báo cáo. Quản trị viên sẽ kiểm tra.');
    }

    public function reactPost(Request $request, Post $post)
    {
        abort_unless($post->PostStatus === 'Đã duyệt', 404);
        $validated = $request->validate([
            'Type' => ['required', 'in:Thích,Yêu thích,Wow,Haha,Buồn,Phẫn nộ'],
        ]);

        $reaction = $post->reactions()->where('UserID', auth()->user()->UserID)->first();
        if ($reaction && $reaction->Type === $validated['Type']) {
            $reaction->delete();
        } elseif ($reaction) {
            $reaction->update(['Type' => $validated['Type']]);
        } else {
            $max = PostReaction::selectRaw('MAX(CAST(SUBSTRING(ReactionID, 3) AS UNSIGNED)) AS m')->value('m');
            $post->reactions()->create([
                'ReactionID' => 'PR'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT),
                'UserID' => auth()->user()->UserID,
                'Type' => $validated['Type'],
            ]);
        }

        return back();
    }

    public function reactComment(Request $request, PostComment $comment)
    {
        abort_unless($comment->Status === 'Hiển thị', 404);
        $validated = $request->validate([
            'Type' => ['required', 'in:Thích,Yêu thích,Wow,Haha,Buồn,Phẫn nộ'],
        ]);

        $reaction = $comment->reactions()->where('UserID', auth()->user()->UserID)->first();
        if ($reaction && $reaction->Type === $validated['Type']) {
            $reaction->delete();
        } elseif ($reaction) {
            $reaction->update(['Type' => $validated['Type']]);
        } else {
            $max = CommentReaction::selectRaw('MAX(CAST(SUBSTRING(ReactionID, 3) AS UNSIGNED)) AS m')->value('m');
            $comment->reactions()->create([
                'ReactionID' => 'CR'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT),
                'UserID' => auth()->user()->UserID,
                'Type' => $validated['Type'],
            ]);
        }

        return back();
    }
}
