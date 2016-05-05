<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Flat UI Free 101 Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="res/lib/flat-ui/css/bootstrap.min.css" rel="stylesheet">
    <!-- Loading Flat UI -->
    <link href="res/lib/flat-ui/css/flat-ui.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="res/lib/flat-ui/img/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="res/lib/flat-ui/js/html5shiv.js"></script>
      <script src="res/lib/flat-ui/js/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body>

  

    <div class="container" style="margin-top: 100px;">
        <div class="pagination">
          <ul>
            <li class="previous">
              <a href="#" class="fui-arrow-left"></a>
            </li>
            <li><a href="#fakelink">1</a></li>
            <li><a href="#fakelink">2</a></li>
            <li><a href="#fakelink">3</a></li>
            <li><a href="#fakelink">4</a></li>
            <li><a href="#fakelink">5</a></li>
            <li><a href="#fakelink">6</a></li>
            <li><a href="#fakelink">7</a></li>
            <li><a href="#fakelink">8</a></li>
            <li><a href="#fakelink">9</a></li>
            <li><a href="#fakelink">10</a></li>
            <!-- Make dropdown appear above pagination -->
            <li class="pagination-dropdown dropup">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fui-triangle-up"></i>
              </a>
              <!-- Dropdown menu -->
              <ul class="dropdown-menu">
                <li>
                  <a href="#">11-20</a>
                  <a href="#">21-30</a>
                  <a href="#">31-40</a>
                </li>
              </ul>
            </li>
        
            <li class="next">
              <a href="#" class="fui-arrow-right"></a>
            </li>
          </ul>
        </div>
    </div>
    
    <div class="container" style="margin-top: 50px;">
        <div class="pagination pagination-minimal">
            <ul>
            <li class="previous">
              <a href="#" class="fui-arrow-left"></a>
            </li>
            <li><a href="#fakelink">1</a></li>
            <li><a href="#fakelink">2</a></li>
            <li><a href="#fakelink">3</a></li>
            <li><a href="#fakelink">4</a></li>
            <li><a href="#fakelink">5</a></li>
            <li><a href="#fakelink">6</a></li>
            <li><a href="#fakelink">7</a></li>
            <li><a href="#fakelink">8</a></li>
            <li><a href="#fakelink">9</a></li>
            <li><a href="#fakelink">10</a></li>
            <!-- Make dropdown appear above pagination -->
            <li class="pagination-dropdown dropup">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fui-triangle-up"></i>
              </a>
              <!-- Dropdown menu -->
              <ul class="dropdown-menu">
                <li>
                  <a href="#">11-20</a>
                  <a href="#">21-30</a>
                  <a href="#">31-40</a>
                </li>
              </ul>
            </li>
            <li class="next">
              <a href="#" class="fui-arrow-right"></a>
            </li>
            </ul>
        </div>
    </div>
  
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <nav class="navbar navbar-default navbar-lg" role="navigation">
                  <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-01">
                      <span class="sr-only">Toggle navigation</span>
                    </button>
                    <a class="navbar-brand" href="#">Flat UI</a>
                  </div>
                  <div class="collapse navbar-collapse" id="navbar-collapse-01">
                    <ul class="nav navbar-nav">
                      <li class="active"><a href="#fakelink">Products</a></li>
                      <li><a href="#fakelink">Features</a></li>
                    </ul>
                    <form class="navbar-form navbar-right" action="#" role="search">
                      <div class="form-group">
                        <div class="input-group">
                          <input class="form-control" id="navbarInput-01" type="search" placeholder="Search">
                          <span class="input-group-btn">
                            <button type="submit" class="btn"><span class="fui-search"></span></button>
                          </span>
                        </div>
                      </div>
                    </form>
                  </div><!-- /.navbar-collapse -->
                </nav>
            </div>        
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-2">
                <p>
                  <button type="button" class="btn btn-primary btn-lg active disabled">Large button</button>
                  <button type="button" class="btn btn-info btn-hg btn-embossed">Large button</button>
                </p>
                <p>
                  <button type="button" class="btn btn-primary">Default button</button>
                  <button type="button" class="btn btn-default">Default button</button>
                </p>
                <p>
                  <button type="button" class="btn btn-primary btn-sm">Small button</button>
                  <button type="button" class="btn btn-default btn-sm">Small button</button>
                </p>
                <p>
                  <button type="button" class="btn btn-primary btn-xs">Extra small button</button>
                  <button type="button" class="btn btn-default btn-xs">Extra small button</button>
                </p>
            </div>
        </div>
    </div>
  
    <div class="container">
        <div class="row">
            <div class="col-md-5" style="background-color: #bbbbbb;">
                <form role="form">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <input type="file" id="exampleInputFile">
                    <p class="help-block">Example block-level help text here.</p>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox"> Check me out
                    </label>
                  </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
            <div class="col-md-5 col-md-offset-2" style="background-color: orange;">
                <form role="form">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <input type="file" id="exampleInputFile">
                    <p class="help-block">Example block-level help text here.</p>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox"> Check me out
                    </label>
                  </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
        
        
    </div>
  
    <div class="container bg-success">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                  <td>1</td>
                  <td>Mark</td>
                  <td>Otto</td>
                  <td>@mdo</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Jacob</td>
                  <td>Thornton</td>
                  <td>@fat</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Larry</td>
                  <td>the Bird</td>
                  <td>@twitter</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="container bg-info">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                  <td>1</td>
                  <td>Mark</td>
                  <td>Otto</td>
                  <td>@mdo</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Jacob</td>
                  <td>Thornton</td>
                  <td>@fat</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Larry</td>
                  <td>the Bird</td>
                  <td>@twitter</td>
                </tr>
            </tbody>
        </table>
    </div>
  
    <div class="container" style="background-color: cyan;">
        <h1>Hello, world!</h1>
    </div>
    
    <div class="container-fluid" style="background-color: black;">
        <h2>fdsfds </h2>
    </div>

    <div class="container-fluid" style="background-color: #eeeeee;">
        <div class="row">
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
        </div>
    </div>
    
    <div class="container-fluid" style="background-color: black;">
        <h2>fdsfds </h2>
    </div>
    
    <div class="container" style="background-color: #eeeeee;">
        <div class="row">
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
            <div class="col-md-1" style="background-color: orange;">.col-md-1</div>
            <div class="col-md-1" style="background-color: #aaaaaa;">.col-md-1</div>
        </div>
    </div>
    
    <p></p>
        
    <div class="container" style="background-color: #bbbbbb;">
        <div class="row">
            <div class="col-md-1" style="background-color: orange;">fdsf</div>
            <div class="col-lg-1" style="background-color: black;">fdsf</div>
        </div>
        
        <div class="row">
          <div class="col-xs-6 col-md-4" style="background-color: orange;">.col-xs-6 .col-md-4</div>
          <div class="col-xs-6 col-md-4" style="background-color: black;">.col-xs-6 .col-md-4</div>
          <div class="col-xs-6 col-md-4" style="background-color: orange;">.col-xs-6 .col-md-4</div>
        </div>
    </div>
    
    <div class="container" style="background-color: #bbbbbb;">
            <div class="row">
                <div class="col-md-9 co-md-pull-3" style="background-color: orange;">fdsf</div>
                <div class="col-lg-3 co-md-push-9" style="background-color: black;">fdsf</div>
                <div class="col-lg-1" style="background-color: black;">fdsf</div>
                <div class="col-lg-1" style="background-color: black;">fdsf</div>
                <div class="col-lg-1" style="background-color: black;">fdsf</div>
            </div>
        <div class="row">
        </div>
    </div>
    
    <!-- /.container -->


    <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
    <script src="res/lib/flat-ui/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="res/lib/flat-ui/js/video.js"></script>
    <script src="res/lib/flat-ui/js/flat-ui.min.js"></script>

  </body>
</html>
