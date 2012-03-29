class free-bets-guide {

  file {'/deploy/docroot/include/config.php':
      ensure  => file,
      content => template('free-bets-guide/config.php.erb'),
    }

  class {"php":}

  class {"apache":}

}
