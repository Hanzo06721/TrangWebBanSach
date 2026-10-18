-- TV1 cần thêm cột Address (đề bài yêu cầu "Cập nhật địa chỉ" trong hồ sơ cá nhân).
-- Chạy 1 lần trên database TrangWebSachDB và báo TV2 cập nhật lại CSDL/ERD cho khớp.
USE TrangWebSachDB;

ALTER TABLE Users
    ADD COLUMN Address VARCHAR(255) NULL AFTER Phone;
