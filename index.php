<?php
require "includes/application.php";
require "includes/header.php";

$info = array(
  array(
    "image"   => "a-c-moore.jpg",
    "caption" => "Portrait of A. C. Moore"
  ),
  array(
    "image"   => "thomas-cooper.jpg",
    "caption" => "Portrait of Thomas Cooper"
  )
);
?>
  <div class="row">
    <div class="col-sm-3">
      <img src="<?php print $application->getURL(); ?>/img/<?php print $info[0]["image"]; ?>" class="img-responsive" alt="<?php print $info[0]["caption"]; ?>">

      <div class="caption"><?php print $info[0]["caption"]; ?></div>
    </div>

    <div class="col-sm-9">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed iaculis commodo erat, porttitor molestie purus. Sed ac euismod nibh. Nulla facilisis dapibus neque, non commodo massa suscipit id. Pellentesque vulputate facilisis pharetra. Suspendisse luctus lorem nec nulla porta dapibus. Aliquam quis gravida metus. Donec id est tempus, lobortis ante egestas, accumsan mauris. Phasellus risus magna, molestie at ipsum quis, viverra tempus quam. Cras vel enim ultrices, vulputate enim ac, dictum neque. Curabitur facilisis venenatis hendrerit. Nulla eget sapien ut magna efficitur finibus vel sed tortor. In vitae odio in leo volutpat mollis eu sit amet dui. Donec eget leo venenatis, elementum augue in, blandit sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse molestie bibendum nisl, eu rutrum lectus fringilla a. Pellentesque at arcu quis ipsum venenatis vehicula.
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-sm-9">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed iaculis commodo erat, porttitor molestie purus. Sed ac euismod nibh. Nulla facilisis dapibus neque, non commodo massa suscipit id. Pellentesque vulputate facilisis pharetra. Suspendisse luctus lorem nec nulla porta dapibus. Aliquam quis gravida metus. Donec id est tempus, lobortis ante egestas, accumsan mauris. Phasellus risus magna, molestie at ipsum quis, viverra tempus quam. Cras vel enim ultrices, vulputate enim ac, dictum neque. Curabitur facilisis venenatis hendrerit. Nulla eget sapien ut magna efficitur finibus vel sed tortor. In vitae odio in leo volutpat mollis eu sit amet dui. Donec eget leo venenatis, elementum augue in, blandit sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse molestie bibendum nisl, eu rutrum lectus fringilla a. Pellentesque at arcu quis ipsum venenatis vehicula.
    </div>

    <div class="col-sm-3">
      <img src="<?php print $application->getURL(); ?>/img/<?php print $info[1]["image"]; ?>" class="img-responsive" alt="<?php print $info[1]["caption"]; ?>">

      <div class="caption"><?php print $info[1]["caption"]; ?></div>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-sm-3">
      <img src="http://placehold.it/200x250" class="img-responsive" alt="Need portrait of Gibbes">

      <div class="caption">Need portrait of Gibbes</div>
    </div>

    <div class="col-sm-9">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed iaculis commodo erat, porttitor molestie purus. Sed ac euismod nibh. Nulla facilisis dapibus neque, non commodo massa suscipit id. Pellentesque vulputate facilisis pharetra. Suspendisse luctus lorem nec nulla porta dapibus. Aliquam quis gravida metus. Donec id est tempus, lobortis ante egestas, accumsan mauris. Phasellus risus magna, molestie at ipsum quis, viverra tempus quam. Cras vel enim ultrices, vulputate enim ac, dictum neque. Curabitur facilisis venenatis hendrerit. Nulla eget sapien ut magna efficitur finibus vel sed tortor. In vitae odio in leo volutpat mollis eu sit amet dui. Donec eget leo venenatis, elementum augue in, blandit sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse molestie bibendum nisl, eu rutrum lectus fringilla a. Pellentesque at arcu quis ipsum venenatis vehicula.
    </div>
  </div>
<?php require "includes/footer.php"; ?>
