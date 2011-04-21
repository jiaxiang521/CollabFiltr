#!/usr/bin/perl

use strict;
use warnings;

my (%users, %items);

load_file('/Volumes/Data/Work/Work/Uni/Dissertation/php/Data/Jester/jester_ratings.dat');

open(my $FH, '>', 'neighboursPearson.txt')
  or die "Could not open neighbourhood file: $!\n";

$| = 1; # no buffering

for my $user (sort keys %users) {
  my @neighbours = get_neighbours($user, 60);
  my $line;

  for my $data (@neighbours) {
    my ($neighbour, $similarity) = @$data;
    
    $line .= "$neighbour $similarity,";
  }
  
  print "$user:$line\n";
  print $FH "$user:$line\n";
}

close $FH;
print "Done\n";

sub load_file {
  my $file = shift;
  
  open(my $FH, '<', $file)
    or die "Could not open file '$file'\n";
    
  while (<$FH>) {
    chomp;
    my ($user, $item, $rating) = split /\t\t/;
    
    $users{$user}{$item} = $rating + 10.0;
    $items{$item}{$user} = $rating + 10.0;
  }
}

sub get_neighbours {
  my ($user_id, $num) = @_;
  my %scores;
  
  for my $user (keys %users) {
    next if $user == $user_id;
    
    my $similarity = get_similarity($user_id, $user);
    next unless $similarity > 0;
    
    $scores{$user} = $similarity;
  }

  my @result;

  my $i;
  for my $user (sort { $scores{$b} <=> $scores{$a} } keys %scores) {
    last if ++$i > $num;
    push @result, [ $user, $scores{$user} ];
  }

  return @result;
}
   
sub get_similarity {
  my ($user1, $user2) = @_;
  
  my (%tmp, %mutuallyRated);
  for my $user (keys %{ $users{$user1} }, keys %{ $users{$user2} }) {
    $tmp{$user}++ && $mutuallyRated{$user}++;
  }

  my @mutuallyRated = keys %mutuallyRated;
  undef %mutuallyRated;
  undef %tmp;
  
  my $length = scalar @mutuallyRated;
  return 0 unless $length > 0;
  
  my ($sum1, $sum2, $sum1Sq, $sum2Sq, $sumPrd);
  for my $index (@mutuallyRated) {
    $sum1   += $users{$user1}{$index};
    $sum2   += $users{$user2}{$index};

    $sum1Sq += $users{$user1}{$index} ** 2;
    $sum2Sq += $users{$user2}{$index} ** 2;
    
    $sumPrd += $users{$user1}{$index} * $users{$user2}{$index};
  }

  my $num = $sumPrd - ($sum1 * $sum2 / $length);
  
  my $den = ($sum1Sq - ($sum1 ** 2) / $length)
             * ($sum2Sq - ($sum2 ** 2) / $length);
  return 0 unless $den > 0;
  
  return $num / sqrt($den);
}