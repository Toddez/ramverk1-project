
-- Table User
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "name" TEXT NOT NULL,
    "password" TEXT NOT NULL,
    "avatar" TEXT DEFAULT NULL
);

-- Table Post
DROP TABLE IF EXISTS Post;
CREATE TABLE Post (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "author" INTEGER NOT NULL,
    "title" TEXT,
    "content" TEXT,
    "type" INTEGER NOT NULL,
    "tags" TEXT,
    "thread" INTEGER DEFAULT NULL,
    "parent" INTEGER,
    "creation" DATETIME NOT NULL,
    "answer" BOOLEAN DEFAULT FALSE
);

-- Table Tag
DROP TABLE IF EXISTS Tag;
CREATE TABLE Tag (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "value" TEXT NOT NULL
);
