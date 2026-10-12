# S.S.R Hotel - Reservation System (PHP + MySQL)

## Setup (XAMPP / WAMP)
1. Start **Apache** and **MySQL** in XAMPP.
2. Copy the `hotel` folder into `C:\xampp\htdocs\`.
3. Open http://localhost/phpmyadmin -> **Import** -> choose `database.sql` -> Go.
   (This version REPLACES the old database - the tables changed, so import it again.)
4. If MySQL has a password, edit it in `config.php`.
5. Edit hotel name, address, phone, email and food price in `config.php`.
6. Open http://localhost/hotel/

## Logins
- Admin: admin@hotel.com / admin123
- Guest: use "Create account"

## Your own photos
Sample pictures are in `images/`. Replace them with real photos using the SAME file name
(.jpg, .png or .webp) and the site will use yours automatically:
- `hotel_outside_1`, `hotel_outside_2`, `hotel_inside_1`, `hotel_inside_2`
- `rooms/101_1` ... `rooms/101_4` (4 images per room; room number + 1 to 4)
When you add a new room in Admin, add `rooms/<roomno>_1..4` images too.

## Features
- Home: welcome paragraph, outside and inside photos, popular rooms, footer with contact and address
- Rooms: check-in/out dates, number of members, food (yes/no), AC / Non-AC, TV, balcony view
- Suggests the best-fit rooms; for smaller rooms it says how many rooms are needed (e.g. 5 members in a 2-person room = 3 rooms) and books them together
- 4 separate photos for every room
- About page: quality, maintenance and customer reviews (logged-in guests can add a review)
- Login / register, My bookings (cancel), Admin dashboard
