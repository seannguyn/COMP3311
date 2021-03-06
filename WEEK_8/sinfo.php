#!/usr/bin/php
<?
require("db.php");

if ($argc < 2 || !is_numeric($argv[1])) exit("Usage: sinfo SID\n");
$sid = $argv[1];

$db = dbConnect("dbname=ass3");

$qry = <<<_SQL_
select p.id
from   People p join Students s on p.id=s.id
where  p.unswid = %d
_SQL_;
$id = dbOneValue($db,mkSQL($qry,$sid));
if (empty($id)) exit("Invalid SID: $sid\n");

$qry = <<<xxSQLxx
select p.title, p.name, p.email, p.gender, c.name as origin
from   People p, Countries c
where  p.id = %d and p.origin = c.id
xxSQLxx;

$sinfo = dbOneTuple($db,mkSQL($qry,$id));

$output = array(
	"SID"     => $sid,
	"Name"    => "$sinfo[title] $sinfo[name]",
	"Email"   => $sinfo["email"],
	"Gender"  => ($sinfo["gender"] == "m" ? "Male" : "Female"),
	"Origin"  => $sinfo["origin"]
);

foreach ($output as $label => $value)
	printf("%-10s: %s\n", $label, $value);
?>