class free-bets-guide {

  file {'/deploy/docroot/include/config.php':
      ensure  => file,
      content => template('free-bets-guide/config.php.erb'),
  }

  file {'/etc/httpd/conf.d/free-bets-guide.conf':
      content => template('free-bets-guide/free-bets-guide.conf.erb'),
      notify => Service['httpd'],
  }

  class {"php":}

  class {"apache":}

}
