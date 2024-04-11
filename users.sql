INSERT INTO users (username, password) VALUES
('user1', '$2y$10$J1Amq2UyW7veIHfp3EFcJOp7GhEQmEvNHXudDdq.q87BwA.3O4C0K'), -- Password: password1
('user2', '$2y$10$z.eiLJ2sAz6VWIXPv9dpGedpF/s10un7Ws/2pgF0IyL69P58rMiZ6'), -- Password: password2
('user3', '$2y$10$1DTI3A0A6.lVQqTKVIb4W.XYutGj.ZlQEVsH9wQ6sfmbEcK0N1xyK'); -- Password: password3

-- The passwords are already hashed using the password_hash() function in PHP. For example, the password for 'user1' is 'password1'.