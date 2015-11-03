SET NAMES utf8;

#-- users
#-- INSERT INTO users (username, password, role_key)
#-- SELECT 'chief_editor', password, 'user_administrator' FROM users WHERE id = 1;

#-- INSERT INTO users (username, password, role_key)
#-- SELECT 'editor', password, 'chief_user' FROM users WHERE id = 1;

#-- INSERT INTO users (username, password, role_key)
#-- SELECT 'general_user', password, 'common_user' FROM users WHERE id = 1;

#-- INSERT INTO users (username, password, role_key)
#-- SELECT 'visitor', password, 'guest_user' FROM users WHERE id = 1;

INSERT INTO users (username, password, `key`, role_key, handlename, status)
SELECT 'chief_editor', password, MD5('chief_editor'), 'administrator', 'chief_editor', '1' FROM users WHERE id = 1;

INSERT INTO users (username, password, `key`, role_key, handlename, status)
SELECT 'editor', password, MD5('chief_editor'), 'common_user', 'editor', '1' FROM users WHERE id = 1;

INSERT INTO users (username, password, `key`, role_key, handlename, status)
SELECT 'general_user', password, MD5('general_user'), 'common_user', 'general_user', '1' FROM users WHERE id = 1;

INSERT INTO users (username, password, `key`, role_key, handlename, status)
SELECT 'visitor', password, MD5('visitor'), 'common_user', 'visitor', '1' FROM users WHERE id = 1;

#-- users_languages
INSERT INTO users_languages (user_id, language_id, name)
SELECT users.id,
     languages.id,
     users.handlename AS name
FROM (users, languages)
LEFT JOIN users_languages ON (users.id = users_languages.user_id AND users_languages.language_id = languages.id)
WHERE users_languages.user_id IS NULL;


#-- user_attributes_users
#-- INSERT INTO user_attributes_users (language_id, user_id, user_attribute_id, `key`, value)
#-- SELECT roles.language_id AS language_id,
#--      users.id AS user_id,
#--      1 AS user_attribute_id,
#--      'nickname' AS 'key',
#--  roles.name AS value
#-- FROM users
#-- INNER JOIN roles ON (users.role_key = roles.key)
#-- LEFT JOIN user_attributes_users ON (users.id = user_attributes_users.user_id AND user_attributes_users.language_id = roles.language_id)
#-- WHERE user_attributes_users.user_id IS NULL;
#-- 

#-- roles_rooms
#-- INSERT INTO roles_rooms (room_id, role_key)
#-- SELECT rooms.id, roles.key
#-- FROM (roles, rooms)
#-- LEFT JOIN roles_rooms ON (roles_rooms.role_key = roles.key AND roles_rooms.room_id = rooms.id)
#-- WHERE roles.type = 2
#-- AND roles_rooms.id IS NULL;


#-- roles_rooms_users
#-- INSERT INTO roles_rooms_users (roles_room_id, user_id)
#-- SELECT roles_rooms.id, users.id
#-- FROM roles_rooms
#-- INNER JOIN users ON (roles_rooms.role_key = users.role_key)
#-- LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
#-- WHERE roles_rooms_users.id IS NULL;

#-- INSERT INTO roles_rooms_users (roles_room_id, user_id)
#-- SELECT roles_rooms.id, users.id
#-- FROM roles_rooms
#-- INNER JOIN user_role_settings ON (roles_rooms.role_key = user_role_settings.default_room_role_key)
#-- INNER JOIN users ON (user_role_settings.role_key = users.role_key)
#-- LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
#-- WHERE roles_rooms_users.id IS NULL
#-- AND roles_rooms.room_id = 1;

#-- INSERT INTO roles_rooms_users (roles_room_id, user_id)
#-- SELECT roles_rooms.id, users.id
#-- FROM roles_rooms
#-- INNER JOIN user_role_settings ON (roles_rooms.role_key = user_role_settings.default_room_role_key)
#-- INNER JOIN users ON (1 = 1)
#-- LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
#-- WHERE roles_rooms_users.id IS NULL
#-- AND roles_rooms.room_id = 2;

#-- INSERT INTO roles_rooms_users (roles_room_id, user_id)
#-- SELECT roles_rooms.id, users.id
#-- FROM roles_rooms
#-- INNER JOIN user_role_settings ON (roles_rooms.role_key = user_role_settings.default_room_role_key)
#-- INNER JOIN users ON (user_role_settings.role_key = users.role_key)
#-- LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
#-- WHERE roles_rooms_users.id IS NULL
#-- AND roles_rooms.room_id = 3;

INSERT INTO roles_rooms_users (roles_room_id, user_id)
SELECT roles_rooms.id, users.id
FROM (roles_rooms, users)
LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
WHERE roles_rooms_users.id IS NULL
AND roles_rooms.role_key = 'room_administrator'
AND roles_rooms.id = 6;

INSERT INTO roles_rooms_users (roles_room_id, user_id)
SELECT roles_rooms.id, users.id
FROM (roles_rooms, users)
LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
WHERE roles_rooms_users.id IS NULL
AND users.id = 1
AND roles_rooms.role_key = 'room_administrator'
AND roles_rooms.id != 6;

#-- INSERT INTO roles_rooms_users (roles_room_id, user_id)
#-- SELECT roles_rooms.id, users.id
#-- FROM (roles_rooms, users)
#-- LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
#-- WHERE roles_rooms_users.id IS NULL
#-- AND users.username = 'chief_editor'
#-- AND roles_rooms.role_key = 'chief_editor'
#-- AND roles_rooms.id != 6;

#-- INSERT INTO roles_rooms_users (roles_room_id, user_id)
#-- SELECT roles_rooms.id, users.id
#-- FROM (roles_rooms, users)
#-- LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
#-- WHERE roles_rooms_users.id IS NULL
#-- AND users.username = 'editor'
#-- AND roles_rooms.role_key = 'editor'
#-- AND roles_rooms.id != 6;

INSERT INTO roles_rooms_users (roles_room_id, user_id)
SELECT roles_rooms.id, users.id
FROM (roles_rooms, users)
LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
WHERE roles_rooms_users.id IS NULL
#-- AND users.username = 'general_user'
AND roles_rooms.role_key = 'general_user'
AND roles_rooms.id > 6;

INSERT INTO roles_rooms_users (roles_room_id, user_id)
SELECT roles_rooms.id, users.id
FROM (roles_rooms, users)
LEFT JOIN roles_rooms_users ON (roles_rooms.id = roles_rooms_users.roles_room_id AND roles_rooms_users.user_id = users.id)
WHERE roles_rooms_users.id IS NULL
#-- AND users.username = 'visitor'
AND roles_rooms.role_key = 'visitor'
AND roles_rooms.id < 6;



#-- room_role_permissions
INSERT INTO room_role_permissions (roles_room_id, permission, value)
SELECT roles_rooms.id, default_role_permissions.permission, default_role_permissions.value
FROM default_role_permissions 
INNER JOIN roles_rooms ON (default_role_permissions.role_key = roles_rooms.role_key)
LEFT JOIN room_role_permissions ON (room_role_permissions.roles_room_id = roles_rooms.id AND room_role_permissions.permission = default_role_permissions.permission)
WHERE default_role_permissions.type = 'room_role'
AND default_role_permissions.fixed = 1
AND room_role_permissions.id IS NULL;


#-- pages
INSERT INTO pages(room_id, lft, rght, permalink, slug, is_published)
SELECT 1, (`id` * 2 + 1), (`id` * 2 + 2), concat('slug_', `key`), concat('slug_', `key`), 1
FROM `plugins`
WHERE plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2';


#-- languages_pages
INSERT INTO languages_pages(language_id, page_id, name)
SELECT plugins.language_id, pages.id, plugins.name
FROM pages, plugins
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2';


#-- containers 
INSERT INTO containers(type, modified_user) 
SELECT 3, plugins.id
FROM plugins
WHERE plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2';


#-- containers_pages 
INSERT INTO containers_pages(page_id, container_id, is_published)
SELECT pages.id, containers.id, 1
FROM pages, plugins, containers
WHERE pages.slug = concat('slug_', plugins.key)
AND containers.type != 3;

INSERT INTO containers_pages(page_id, container_id, is_published)
SELECT pages.id, containers.id, 1
FROM pages, plugins, containers
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.id= containers.modified_user
AND containers.`type` = 3 AND containers.id != 3;


#-- boxes 
INSERT INTO boxes(container_id, `type`, space_id, room_id, page_id)
SELECT containers_pages.container_id, 4, 1, 1, containers_pages.page_id
FROM containers_pages
INNER JOIN containers ON (containers_pages.container_id = containers.id)
LEFT JOIN boxes ON (containers.id = boxes.container_id)
WHERE boxes.id IS NULL;


#-- boxes_pages 
INSERT INTO boxes_pages(page_id,box_id, is_published)
SELECT containers_pages.page_id, boxes.id, 1
FROM boxes
INNER JOIN containers_pages ON (boxes.container_id = containers_pages.container_id);


#-- frames 
INSERT INTO frames (language_id, room_id, box_id, plugin_key, `key`, name, header_type, weight, is_deleted)
SELECT plugins.language_id, 1, boxes.id, plugins.key, MD5(plugins.key), CONCAT('タイトル-', plugins.name), 'default', 1, 0 
FROM plugins
INNER JOIN pages ON (pages.slug = concat('slug_', plugins.key))
INNER JOIN boxes ON (pages.id = boxes.page_id)
WHERE boxes.type = 4 AND boxes.id != 3
AND plugins.key != 'menus'
AND plugins.language_id = '2';
