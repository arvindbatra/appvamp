#!/usr/bin/perl
# import module

use Getopt::Long;

my $user = '';
my $pass = '';
my $host = '';
my $database = '';
# read options
$result = GetOptions ( "u=s" => \$user,
	"p=s" => \$pass,
	"d=s" => \$database,
	"h=s" => \$host );  

sub usage() {
	print "perl runscripts.pl --u=<user> --p=<password> --h=<host> --d=<database>\n";
}


print "$user\n";
print "$pass\n";
print "$host\n";
print "$database\n";
if ($user eq '' or $pass eq '' or $host eq '' or $database eq '')
{
	print ("Error in syntax\n");
	usage();
	exit();
}


$d = '.';

opendir(DIR, $d);
my @files = readdir(DIR);
closedir(DIR);
 @files = sort(@files);
foreach my $file (@files)
{
	if($file =~ m/.sql$/) {
		print "file: $file\n";
		my$output = '';

		$checkCmd = "\'select * from  DBStatus where script_name = \"$file\"\'";
		$output = qx/mysql --user=$user --password=$pass --host=$host --database=$database -e $checkCmd /;
		print "$output\n";
		my $exists = 0;
		if( $output =~ m/$file/) {
			print "$file already exists, Skipping!\n";
			$exists = 1;
		}

		if($exists == 0) {
			$output = qx/mysql --verbose --user=$user --password=$pass --host=$host --database=$database < $file  2>&1/;
			print "$output\n";
			my $status =  $? >>8;
			if($status == 1) 
			{
				print "Error in executing command from file $file \n";
				exit(-1);
			}
			else
			{
				print "cmd done\n";
				#file executed, insert into database.
				my $insertCmd = "\'insert into DBStatus (script_name, created_at) values (\"$file\", NOW())\'";
				$output = qx/mysql --verbose --user=$user --password=$pass --host=$host --database=$database -e $insertCmd /;
				print "$output\n";
			}
		}
	}
}

