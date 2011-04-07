<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Calerrific</title>
		<script type = "text/javascript" src = "/js/jquery-1.5.1.min.js"></script>
		<script type = "text/javascript" src = "/js/jquery-ui-1.8.11.custom.min.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/jquery.ui.timepicker.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/live_view.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/dateUtils.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/calendar.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/edit_user.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/login.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/event.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/users.js"></script>
    <script type = "text/javascript" charset = "utf-8" src = "/js/tagger.js"></script>
    <script type="text/javascript" src="http://jqueryui.com/themeroller/themeswitchertool/"></script>
    <script type="text/javascript">
      $(".ui-state-default").live("mouseover", function() {
        $(this).addClass("ui-state-hover")
      })
      $(".ui-state-default").live("mouseout", function() {
        $(this).removeClass("ui-state-hover")
      })
      function resetHeight() {
        var viewport = Math.max($(window).height() - 90, 420)
        $(".days").css({height: viewport})
      }
      $(function() {
        resetHeight()
        $(window).bind("resize", resetHeight)
        $('#switcher').themeswitcher({loadTheme: "Vader"});
        $("#ui-datepicker-div").hide();
      })
    </script>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css' />
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link type="text/css" rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/themes/base/jquery-ui.css" />
  </head>
  <body class = "logged-out ui-widget-content">
    <div id="switcher"></div>
    <div id = "nav" class = "ui-widget-header">
      <div id = "view-switcher">
        <div id = "month-view" class = "ui-state-default ui-corner-left">month</div>
        <div id = "week-view" class = "ui-state-default">week</div>
        <div id = "event-view" class = "ui-state-default ui-corner-right">event</div>
        <div id = "edit-user-info" class = "logged-in ui-state-default ui-corner-all">update profile</div>
        <div id = "create-event-button" class = "logged-in ui-state-default ui-corner-all">create event</div>
        <div id = "logout-button" class = "logged-in button ui-state-default ui-corner-all">logout</div>
        <div id = "login-button" class = "logged-out button ui-state-default ui-corner-all">login</div>
      </div>
      <div id = "month-controls" style = "display:none;">
        <span id = "previous-month" class = " button ui-state-default ui-corner-all">Previous month</span>
        <span class = "current-date">
          <span class = "current-month"></span>
          <span class = "current-year"></span>
        </span>
        <span id = "next-month" class = "button ui-state-default ui-corner-all">Next month</span>
      </div>
      <div id = "week-controls">
        <span id = "previous-week" class = "button ui-state-default ui-corner-all">Previous week</span>
        <span class = "current-date">
          <span class = "start-of-week"></span> - 
          <span class = "end-of-week"></span>
        </span> 
        <span id = "next-week" class = "button ui-state-default ui-corner-all">Next week</span>
      </div>
      <input type = "text" id = "search-box" style = "float: right;" />
    </div>
    <div id = "calendar-month" style = "display:none;">
      <ul class = "week-header">
        <li class = "span-week">Sunday</li>
        <li class = "span-week">Monday</li>
        <li class = "span-week">Tuesday</li>
        <li class = "span-week">Wednesday</li>
        <li class = "span-week">Thursday</li>
        <li class = "span-week">Friday</li>
        <li class = "span-week">Saturday</li>
      </ul>
      <ul class = "days">
        <li class = "day span-week ui-widget-content ui-corner-all">
          <div class = "date ui-widget-header ui-corner-top"></div>
          <div class = "events">
            <div class = "event">
              <div>
                <span class = "name"></span> - <span class = "start-time"></span>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>  
    
    <div id = "calendar-week">
      <ul class = "week-header">
        <li class = "span-week">Sunday</li>
        <li class = "span-week">Monday</li>
        <li class = "span-week">Tuesday</li>
        <li class = "span-week">Wednesday</li>
        <li class = "span-week">Thursday</li>
        <li class = "span-week">Friday</li>
        <li class = "span-week">Saturday</li>
      </ul> 
      <ul class = "days">
        <li class = "day span-week ui-widget-content">
          <div class = "date"></div>
          <div class = "events">
            <div class = "event week-event">
              <div> 
                <span class = "name"></span> - <span class = "start-time"></span>
              </div>
              <div class = "tags"></div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div id = "calendar-event" class = "ui-widget-content ui-corner-all">
      <h1 class = "ui-widget-header ui-corner-top">Events List</h1>
      <div class = "error" style = "display:none;">Nothing found!</div>
      <div class = "events">
        <div class = "event">
          <div> 
            <span class = "name"></span> - <span class = "start-time"></span>
          </div>
          <div>Location:</div>
          <span class = "location"></span>
          <div>Tags:</div>
          <span class = "tags">
            <span>
              <span class = "name"></span>,&nbsp;
            </span>
          </span>
          <div>Attendees:</div>
          <span class = "attendees">
            <span>
              <span class = "name"></span>,&nbsp;
            </span>
          </span> 
          <div>Owner:</div>
          <span class = "ownername"></span> 
          <div class = "event-controls">
            <button class = "edit-event-button ui-state-default ui-corner-all">edit</button>
            <button class = "cancel-event-button ui-state-default ui-corner-all">cancel</button>
            <button class = "attend-event-button ui-state-default ui-corner-all">Attend Event</button>
          </div>
        </div> 
      </div>
    </div>
    <div id = "calendar-user" class = "ui-widget-content ui-corner-all">
      <h1 class = "ui-widget-header ui-corner-top">Events List</h1>
      <div class = "title"></div>
      <div class = "name"></div>
      <div class = "department"></div>
      <div class = "username"></div>
      <div class = "position"></div>
      <div class = "email"></div>
    </div>
    <div id = "user-edit">
      <form id="user-edit-form" action="" title="Edit user" method="post">
        <fieldset>
          <label>Name</label> <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all"/>
          <label>Position</label> <input type="text" name="position" id="position" class="text ui-widget-content ui-corner-all"/>
          <label>Email</label> <input type="text" name="email" id="email" class="text ui-widget-content ui-corner-all" />
          <label>Title</label> <input type="text" name="title" id="title" class="text ui-widget-content ui-corner-all" />
          <label>Department</label> <input type="text" name="department" id="department" class="text ui-widget-content ui-corner-all" />
          <label>Password</label> <input type="password" name="password" id="password" class="text ui-widget-content ui-corner-all" />
          <label>Confirm Password</label> <input type="password" name="confirm_password" id="confirm_password" class="text ui-widget-content ui-corner-all" />
          <input type="hidden" id="event-id" name="id" />
        </fieldset>
      </form> 
    </div>
    <div id = "event-create" title="Create new event">
      <form id = "create-form" method = "post" action="">
        <fieldset>
          <label>Name</label> <input type="text" name="name" id="event-name" class="text ui-widget-content ui-corner-all" />
          <label>Description</label> <input type="text" name="description" id="event-description" class="text ui-widget-content ui-corner-all" />
          <label>Start Date</label> <input type="text" name="start_date" id="event-start-date" class="text ui-widget-content ui-corner-all" />
          <label>Start Time</label> <input type="text" name="start_time" id="event-start-time" class="text ui-widget-content ui-corner-all" />
          <label>End Date</label> <input type="text" name="end_date" id="event-end-date" class="text ui-widget-content ui-corner-all" />
          <label>End Time</label> <input type="text" name="end_time" id="event-end-time" class="text ui-widget-content ui-corner-all" />
          <label>Location</label> <input type="text" name="location" id="event-location" class="text ui-widget-content ui-corner-all" />
          <label>Tags</label> <input type="text" name="tags" id="event-tags" class="text ui-widget-content ui-corner-all" />
          <input type="hidden" id="id" name="id" />
        </fieldset>      
      </form> 
    </div> 
    <div id = "login" title="Log In">
       <form action="/users/login" method="get" accept-charset="utf-8">
         <fieldset>
           <div class="ui-state-error ui-corner-all errors" style = "padding: 0pt 0.7em; display: none;"> 
             <p>
               <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> 
               <strong>Error:</strong> <span class = "message">Wrong username or password</span>
             </p>
           </div> 
           <label>Username</label> 
           <input type="text" name = "username" id = "username" class="text ui-widget-content ui-corner-all" />
           <label>Password</label> 
           <input type="password" name = "password" id = "pw" class="text ui-widget-content ui-corner-all" />
         </fieldset>
       </form>
    </div>
  </body>
</html>

