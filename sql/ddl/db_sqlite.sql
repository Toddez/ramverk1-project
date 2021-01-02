
-- Table Users
DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "name" TEXT NOT NULL,
    "password" TEXT NOT NULL,
    "avatar" TEXT DEFAULT NULL
);

-- Table Post
DROP TABLE IF EXISTS Post;
CREATE TABLE Post (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "parent" INTEGER DEFAULT NULL,
    "creation" DATETIME DEFAULT (DATETIME('now')),
    "answer" BOOLEAN DEFAULT FALSE
);

-- Table for correlating which user upvoted/downvoted which post