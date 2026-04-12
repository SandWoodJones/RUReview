PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    is_admin INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS daily_menu (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS meal (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    daily_menu_id INTEGER NOT NULL REFERENCES daily_menu(id) ON DELETE CASCADE,
    type TEXT NOT NULL CHECK (type IN ('almoco', 'janta')),
    protein TEXT NOT NULL,
    protein_vegan TEXT NOT NULL,
    beans TEXT NOT NULL CHECK (beans in ('preto', 'carioca')),
    carb_extra TEXT NOT NULL,
    salad_extra TEXT NOT NULL,
    dessert TEXT NOT NULL,

    UNIQUE (daily_menu_id, type)
);

CREATE TABLE IF NOT EXISTS review (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES user(id),
    meal_id INTEGER NOT NULL REFERENCES meal(id),
    rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TEXT NOT NULL DEFAULT (datetime('now')),

    UNIQUE (user_id, meal_id)
);

CREATE TABLE IF NOT EXISTS image (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    review_id INTEGER NOT NULL REFERENCES review(id) ON DELETE CASCADE,
    image_data BLOB NOT NULL,
    mime_type TEXT NOT NULL
)
