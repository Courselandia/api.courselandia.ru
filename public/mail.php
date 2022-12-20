<?php

echo $_GET["email"];
echo "<br>";
mail($_GET["email"], "This is text", "This is message");
echo "SEND";
