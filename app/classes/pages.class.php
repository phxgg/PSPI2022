<?php
class Pages
{
  public static $main = [
    'index' => [
      'title' => 'Αρχική',
      'subtitle' => 'Dashboard',
      'iconClass' => 'bi bi-house-door text-primary'
    ],
    'login' => [
      'title' => 'Σύνδεση',
      'subtitle' => 'Into a new paradise',
      'iconClass' => 'bi bi-box-arrow-in-right text-primary'
    ],
    'register' => [
      'title' => 'Εγγραφή',
      'subtitle' => 'Join us!',
      'iconClass' => 'bi bi-plus-square text-primary'
    ],
    'settings' => [
      'title' => 'Ρυθμίσεις',
      'subtitle' => 'Ο λογαριασμός μου',
      'iconClass' => 'bi bi-gear-wide-connected text-dark'
    ],
    'professional' => [
      'title' => 'Επαγγελματίας',
      'subtitle' => 'Διαχείρηση των καταστημάτων μου',
      'iconClass' => 'bi bi-patch-check text-primary'
    ],
    'edit-description' => [
      'title' => 'Επεξεργασία περιγραφής',
      'subtitle' => 'Επεξεργασία περιγραφής',
      'iconClass' => 'bi bi-pencil-square text-primary'
    ],
    'contact' => [
      'title' => 'Επικοινωνία',
      'subtitle' => 'Πες μας ένα γεια!',
      'iconClass' => 'bi bi-chat-dots text-primary'
    ],
    'categories' => [
      'title' => 'Κατηγορίες',
      'subtitle' => 'Κατηγορίες εστίασης',
      'iconClass' => 'bi bi-tags'
    ],
    'view-category' => [
      'title' => 'Προβολή κατηγορίας',
      'subtitle' => 'Προβολή κατηγορίας',
      'iconClass' => 'bi bi-tags'
    ],
    'view-store' => [
      'title' => 'Προβολή καταστήματος',
      'subtitle' => 'Προβολή καταστήματος',
      'iconClass' => 'bi bi-shop'
    ],
    'my-favorites' => [
      'title' => 'Αγαπημένα',
      'subtitle' => 'Η λίστα με τα αγαπημένα μου',
      'iconClass' => 'bi bi-balloon-heart-fill text-danger'
    ],
    'my-bookings' => [
      'title' => 'Κρατήσεις',
      'subtitle' => 'Η λίστα με τις κρατήσεις που έχω κάνει',
      'iconClass' => 'bi bi-bookmark-star text-success'
    ],
    'book' => [
      'title' => 'Κράτηση',
      'subtitle' => 'Κάνε μία κράτηση',
      'iconClass' => 'bi bi-bookmark-star'
    ],
    'search' => [
      'title' => 'Αναζήτηση',
      'subtitle' => 'Βρες ένα κατάστημα',
      'iconClass' => 'bi bi-search'
    ],
  ];

  public static $admin = [
    'dashboard' => [
      'title' => 'Dashboard',
      'subtitle' => 'Dashboard',
      'iconClass' => 'bi bi-grid text-deep-purple'
    ],
    'manage-users' => [
      'title' => 'Χρήστες',
      'subtitle' => 'Διαχείρηση χρηστών',
      'iconClass' => 'bi bi-people text-deep-purple'
    ],
    'manage-categories' => [
      'title' => 'Κατηγορίες',
      'subtitle' => 'Διαχείριση κατηγοριών',
      'iconClass' => 'bi bi-tags text-deep-purple'
    ],
    'manage-stores' => [
      'title' => 'Καταστήματα',
      'subtitle' => 'Διαχείριση καταστημάτων',
      'iconClass' => 'bi bi-shop text-deep-purple'
    ],
  ];
}
