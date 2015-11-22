SET NAMES utf8;

#-- --------------------
#-- users
#-- --------------------
INSERT INTO users (username, password, `key`, role_key, handlename, status, timezone)
SELECT 'chief_editor', password, MD5('chief_editor'), 'administrator', 'chief_editor', '1', 'Asia/Tokyo' 
FROM users WHERE id = 1;

INSERT INTO users (username, password, `key`, role_key, handlename, status, timezone)
SELECT 'editor', password, MD5('chief_editor'), 'common_user', 'editor', '1', 'Asia/Tokyo' 
FROM users WHERE id = 1;

INSERT INTO users (username, password, `key`, role_key, handlename, status, timezone)
SELECT 'general_user', password, MD5('general_user'), 'common_user', 'general_user', '1', 'Asia/Tokyo' 
FROM users WHERE id = 1;

INSERT INTO users (username, password, `key`, role_key, handlename, status, timezone)
SELECT 'visitor', password, MD5('visitor'), 'common_user', 'visitor', '1', 'Asia/Tokyo' 
FROM users WHERE id = 1;

#-- --------------------
#-- users_languages
#-- --------------------
INSERT INTO users_languages (user_id, language_id, name)
SELECT users.id,
     languages.id,
     users.handlename AS name
FROM (users, languages)
LEFT JOIN users_languages ON (users.id = users_languages.user_id AND users_languages.language_id = languages.id)
WHERE users_languages.user_id IS NULL;


#-- --------------------
#-- pages
#-- --------------------
INSERT INTO pages(room_id, lft, rght, permalink, slug, is_published)
SELECT 1, (@i := @i + 1), (@i := @i + 1), concat('slug_', `key`), concat('slug_', `key`), 1
FROM (select @i:=max(rght) from pages) as dummy, `plugins`
WHERE plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2'
ORDER BY plugins.id;

INSERT INTO pages(room_id, lft, rght, permalink, slug, is_published)
SELECT (@r := @r + 1), (@i := @i + 1), (@i := @i + 1), concat('slug_', `username`), concat('slug_', `username`), 1
FROM (select @r:=max(room_id) from pages) as dummy1, (select @i:=max(rght) from pages) as dummy2, `users`
WHERE users.id != 1
ORDER BY users.id;


#-- --------------------
#-- languages_pages
#-- --------------------
INSERT INTO languages_pages(language_id, page_id, name)
SELECT plugins.language_id, pages.id, plugins.name
FROM pages, plugins
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2'
ORDER BY plugins.id;

INSERT INTO languages_pages(language_id, page_id, name)
SELECT '2', pages.id, 'Top'
FROM pages, users
WHERE pages.slug = concat('slug_', users.username)
AND users.id != 1
ORDER BY users.id;


#-- --------------------
#-- containers 
#-- --------------------
INSERT INTO containers(id, type) 
SELECT (@c1 := @c1 + 1), 3
FROM (select @c1:=max(id) from containers) as dummy, plugins
WHERE plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2'
ORDER BY plugins.id;

INSERT INTO containers(id, type) 
SELECT (@c1 := @c1 + 1), 3
FROM (select @c1:=max(id) from containers) as dummy, users
WHERE users.id != 1
ORDER BY users.id;


#-- --------------------
#-- containers_pages 
#-- --------------------
INSERT INTO containers_pages(page_id, container_id, is_published)
SELECT pages.id, containers.id, 1
FROM pages, plugins, containers
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.language_id = '2'
AND containers.type != 3;

INSERT INTO containers_pages(page_id, container_id, is_published)
SELECT pages.id, (@c1 := @c1 + 1), 1
FROM (select @c1:=max(container_id) from containers_pages) as dummy, pages, plugins
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2'
ORDER BY plugins.id;

INSERT INTO containers_pages(page_id, container_id, is_published)
SELECT pages.id, containers.id, 1
FROM pages, users, containers
WHERE pages.slug = concat('slug_', users.username)
AND containers.type != 3;

INSERT INTO containers_pages(page_id, container_id, is_published)
SELECT pages.id, (@c1 := @c1 + 1), 1
FROM (select @c1:=max(container_id) from containers_pages) as dummy, pages, users
WHERE pages.slug = concat('slug_', users.username)
AND users.id != 1
ORDER BY users.id;


#-- --------------------
#-- boxes 
#-- --------------------
INSERT INTO boxes(container_id, `type`, space_id, room_id, page_id)
SELECT containers_pages.container_id, 4, 1, 1, containers_pages.page_id
FROM plugins, containers_pages, containers, pages
WHERE containers_pages.container_id = containers.id
AND containers_pages.page_id = pages.id
AND pages.slug = concat('slug_', plugins.key)
AND containers.type = 3
AND plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2'
ORDER BY plugins.id;

INSERT INTO boxes(container_id, `type`, space_id, room_id, page_id)
SELECT containers_pages.container_id, 4, 3, pages.room_id, containers_pages.page_id
FROM users, containers_pages, containers, pages
WHERE containers_pages.container_id = containers.id
AND containers.type = 3
AND containers_pages.page_id = pages.id
AND pages.slug = concat('slug_', users.username)
AND users.id != 1
ORDER BY users.id;



#-- --------------------
#-- boxes_pages 
#-- --------------------
INSERT INTO boxes_pages(page_id, box_id, is_published)
SELECT containers_pages.page_id, boxes.id, 1
FROM boxes
INNER JOIN containers_pages ON (boxes.container_id = containers_pages.container_id);


#-- --------------------
#-- frames 
#-- --------------------
INSERT INTO frames (language_id, room_id, box_id, plugin_key, `key`, name, header_type, weight, is_deleted)
SELECT plugins.language_id, 1, boxes.id, plugins.key, MD5(plugins.key), CONCAT('タイトル-', plugins.name), 'default', (@i := @i + 1), 0 
FROM (select @i:=2) as dummy, plugins
INNER JOIN pages ON (pages.slug = concat('slug_', plugins.key))
INNER JOIN boxes ON (pages.id = boxes.page_id)
WHERE boxes.type = 4 AND boxes.id != 3
AND plugins.key != 'menus'
AND plugins.language_id = '2';



#-- --------------------
#-- rooms
#-- --------------------
INSERT INTO rooms(space_id, page_id_top, root_id, parent_id, lft, rght, active, default_role_key, need_approval, default_participation, page_layout_permitted)
SELECT '3', pages.id, '2', '2', (@i := @i + 1), (@i := @i + 1), '1', 'room_administrator', '0', '0', '0'
FROM (select @i:=max(rght) from rooms where parent_id = '2') as dummy, pages, users
WHERE pages.slug = concat('slug_', users.username)
AND users.id != 1
ORDER BY users.id;

UPDATE rooms, (select max(rght)+1 as max_rght from rooms where parent_id = '2') as dummy
SET rght = dummy.max_rght WHERE id = 2;

UPDATE rooms, (select max(rght)+1 as max_rght from rooms) as dummy
SET lft = dummy.max_rght, rght = dummy.max_rght+1 WHERE id = 3;


#-- --------------------
#-- rooms_languages
#-- --------------------
INSERT INTO rooms_languages(language_id, room_id, name)
SELECT '1', rooms.id, 'My room'
FROM rooms
LEFT JOIN rooms_languages ON (rooms.id = rooms_languages.room_id AND rooms_languages.language_id = '1')
WHERE rooms_languages.id IS NULL;

INSERT INTO rooms_languages(language_id, room_id, name)
SELECT '2', rooms.id, 'マイルーム'
FROM rooms
LEFT JOIN rooms_languages ON (rooms.id = rooms_languages.room_id AND rooms_languages.language_id = '2')
WHERE rooms_languages.id IS NULL;


#-- --------------------
#-- roles_rooms
#-- --------------------
INSERT INTO roles_rooms(room_id, role_key)
SELECT rooms.id, 'room_administrator'
FROM rooms
LEFT JOIN roles_rooms ON (rooms.id = roles_rooms.room_id)
WHERE roles_rooms.id IS NULL;


#-- --------------------
#-- roles_rooms_users
#-- --------------------
INSERT INTO roles_rooms_users (roles_room_id, user_id, room_id)
SELECT roles_rooms.id, users.id, roles_rooms.room_id
FROM roles_rooms
INNER JOIN users ON (roles_rooms.role_key = users.username)
WHERE roles_rooms.room_id IN (1, 2, 3);

INSERT INTO roles_rooms_users (roles_room_id, user_id, room_id)
SELECT roles_rooms.id, users.id, roles_rooms.room_id
FROM pages, users, rooms, roles_rooms
WHERE pages.slug = concat('slug_', users.username)
AND users.id != 1
AND pages.id = rooms.page_id_top
AND roles_rooms.room_id = rooms.id
ORDER BY users.id;


#-- --------------------
#-- room_role_permissions
#-- --------------------
INSERT INTO room_role_permissions (roles_room_id, permission, value)
SELECT roles_rooms.id, room_role_permissions.permission, room_role_permissions.value
FROM pages, users, rooms, roles_rooms, room_role_permissions
WHERE pages.slug = concat('slug_', users.username)
AND users.id != 1
AND pages.id = rooms.page_id_top
AND roles_rooms.room_id = rooms.id
AND room_role_permissions.roles_room_id = 6
ORDER BY users.id;


#-- --------------------
#-- plugins_rooms
#-- --------------------
INSERT INTO plugins_rooms (room_id, plugin_key)
SELECT rooms.id, plugins.key
FROM pages, users, rooms, plugins
WHERE pages.slug = concat('slug_', users.username)
AND users.id != 1
AND pages.id = rooms.page_id_top
AND plugins.type = 1
AND plugins.language_id = '2'
ORDER BY users.id;

