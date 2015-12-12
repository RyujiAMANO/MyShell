SET NAMES utf8;

#-- --------------------
#-- roles_rooms_users
#-- --------------------
DELETE FROM roles_rooms_users WHERE user_id IN (2, 3, 4, 5) AND room_id IN (1);

INSERT INTO roles_rooms_users (roles_room_id, user_id, room_id)
SELECT roles_rooms.id, users.id, roles_rooms.room_id
FROM roles_rooms
INNER JOIN users ON (roles_rooms.role_key = users.username)
WHERE roles_rooms.room_id IN (1);
