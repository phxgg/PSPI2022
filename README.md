# PSPI Project 2022

### Admin Default Credentials:

Username `admin`

Password `admin`

### Professional Default Credentials

Username `prof`

Password `prof`

### User Default Credentials

Username `user`

Password `user`

## ToDo:

Indicative list of things to be done. There's way more things to do, but not included in this list right now.

* Documentation for functions and usage.
* <s>Professional `display and manage bookings`</s>
* <s>`view-store` page</s>
* <s>Update bookings' `done` column to `1` when booking time >= now. Also update the code to be taking `done` into consideration for certain things.
  When a booking is marked as `done`, set store's `reserved` column to `reserved - 1`.</s>
* <s>* **URGENT!** If a category has been removed by an admin, also remove it from every store.</s>
* <s>`edit-description` page with BBCode support. BBCode will also be available in `view-store` body.</s>
* <s>**URGENT!** Decode HTML tags when content is displayed (`categories`, `view-category`, `view-store`, etc...)</s>
* <s>Booking system</s>
* <s>**Low** `ajax.php` - Use `===` instead of `==` when checking whether `$data` is `NULL`.</s>
* <s>Alert user when a store has not set up its opening and closing hours, and disable from booking tables. Show a warning icon.</s>
* <s>Save store open days, and also opening and closing hours.</s>
* <s>Professional `add store`</s>
* <s>Professional `manage store`</s>
* <s>Professional `upload pdf documents when a new store has been added`</s>
* <s>Maybe each professional account is allowed to 1 store only??? - no</s>
* <s>Admin `user list`, `category list`, `stores list` - DataTables for displaying / Modals & AJAX for edit and delete buttons.</s>
* <s>Admin `manage user`</s>
* <s>Admin `add category`</s>
* <s>Admin `manage category`</s>
* <s>Admin `approve store addition`</s>
* <s>Use ajax in most of forms.</s>
* <s>Admin - Add, edit and delete users, categories, stores. Use modals, see https://getbootstrap.com/docs/5.0/components/modal/#varying-modal-content</s>
* <s>Make the add user ajax. View `manage-users.tpl.php`</s>
* <s>Registration page user/professional rank.</s>
* <s>Save user phone number.</s>
* <s>Save maximum number of people per table for each store.</s>
* <s>Search bar: display results using a dropdown?</s>
* <s>Category displaying properly and not in a table.</s>
* <s>User first, last name (and a picture of themselves?).</s>
* <s>User `favorites` system</s>
* <s>Search through categories.</s>
* <s>Search through store title, description, etc. in `view-category`</s>
* <s>Display stores using a bootstrap `card` instead of tables. (Must adjust search filtering.)</s>

## Unfinished:

* See if we could implement multiple opening and closing hours per day (current database is implemented already).
* Booking page: Exclude certain days and time ranges that stores are not open from the DatePicker and TimePicker inputs.
* Did not have the time to implement BBCode parsing on client-side in `view-category` page (`_displayStore()` in `main.js`).
* Maybe display the whole store address in `view-category` so it becomes filterable? or find a way to both make it searchable, and look nice.
* `professional` page - Load content when a tab has been shown. View https://getbootstrap.com/docs/5.0/components/navs-tabs/#events for more info.
* Responsiveness is not working as expected. Unfortuneately, we did not have the time to fix the admin sidebar and the table showing issues.
