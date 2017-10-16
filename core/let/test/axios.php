<!DOCTYPE html>
<html>
  <head>
    <title>axios - all example</title>
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"/>
  </head>
  <body class="container">
    <h1>axios.all</h1>

    <div>
      <h3>User</h3>
      <div class="row">
        <img id="useravatar" src="" class="col-md-1"/>
        <div class="col-md-3">
          <strong id="username"></strong>
        </div>
      </div>
      <hr/>
      <h3>Orgs</h3>
      <ul id="orgs" class="list-unstyled"></ul>
    </div>

    <script src="https://cdn.bootcss.com/axios/0.16.2/axios.min.js"></script><!-- https://github.com/axios/axios -->
    <script>
      axios.all([
        axios.get('https://api.github.com/users/mzabriskie'),
        axios.get('https://api.github.com/users/mzabriskie/orgs')
      ]).then(axios.spread(function (user, orgs) {
        document.getElementById('useravatar').src = user.data.avatar_url;
        document.getElementById('username').innerHTML = user.data.name;
        document.getElementById('orgs').innerHTML = orgs.data.map(function (org) {
          return (
            '<li class="row">' +
              '<img src="' + org.avatar_url + '" class="col-md-1"/>' +
              '<div class="col-md-3">' +
                '<strong>' + org.login + '</strong>' +
              '</div>' +
            '</li>'
          );
        }).join('');
      }));
    </script>
    <!-- https://github.com/axios/axios/tree/master/examples
        axios examples

        To run the examples:

        git clone https://github.com/axios/axios.git
        cd axios
        npm install
        grunt build
        npm run examples
        http://localhost:3000
    -->
  </body>
</html>