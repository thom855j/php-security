<ul>
    <li><a href="index.php?example=session">Session</a></li>
    <li><a href="index.php?example=cookie">Cookie</a></li>
    <li><a href="index.php?example=hash">Hash</a></li>
    <li><a href="index.php?example=password">Password</a></li>
    <li><a href="index.php?example=token">Token</a></li>
    <li><a href="index.php?example=validator">Validator</a></li>
</ul>
<?php
if (isset($_GET['example']))
{
    if (file_exists($_GET['example'] . '.php'))
    {
        require_once $_GET['example'] . '.php';
    }
    else
    {
        require_once 'index.php';
    }
}

