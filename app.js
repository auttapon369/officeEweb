var app = angular.module('ewebApp', ['ngRoute']);

app.factory
(
	'$_global',
	[
		'$http',
		function($http)
		{
			return {

				request : function(_url, _vars)
				{
                	if ( typeof _vars !== 'undefined' )
                	{
                    	return $http
						(
							{
								headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
								method: "POST",
								url: _url,
								data: $.param(_vars)
							}
						);
                	}
                	else
                	{
						return $http.get(_url);
					}
				},
            	conv : function(_obj, _id, _where)
            	{
					angular.forEach
					(
						_obj,
						function(value)
						{
							if (_where == value[_id])
							{
								_where = value.id;
							}
						}
					);

					return _where;
				},
				abs : function(_obj, _where)
            	{
            		var n = 0;
					angular.forEach
					(
						_obj,
						function(value)
						{
							if (_where == value['code'])
							{
								n++;
							}
						}
					);

					return n;
				},
				grant : function(_obj, _id)
		  		{
		  			var x = false;
					angular.forEach
					(
						_obj,
						function(value)
						{
							if (value['code']==_id)
							{
								x = true;
							}
						}
					);

					return x;
				},
				self : function(_obj, _id)
		  		{
		  			var x = false;
					angular.forEach
					(
						_obj,
						function(value)
						{
							if (value['name']==_id)
							{
								x = true;
							}
						}
					);

					return x;
				},
				dayDiff : function(first, second)
            	{
					var date1 = new Date(first);
					var date2 = new Date(second);
					//var timeDiff = Math.abs(date2.getTime() - date1.getTime());
					var timeDiff = date2.getTime() - date1.getTime();
					var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

					return diffDays;
				}
			}
		}
	]
);

app.config
(
	[
		"$routeProvider",
		function($routeProvider)
		{
			$routeProvider
			.when
			(
				'/',
				{
					templateUrl: 'app/calendar/main.html',
					controller: 'calendarBox',
					controllerAs: 'ca'
				}
			)
			.when
			(
				'/add',
				{
					templateUrl: 'lib/template/add.html',
					controller: 'menuCtrl',
					controllerAs: 'm'
	    		}
			)			
			.when
			(
				'/reminder',
				{
	      			redirectTo: function()
	      			{
	      				var today = new Date().getFullYear();
        				return "/reminder/" + today;
      				}
	    		}
			)
			.when
			(
				'/reminder/new',
				{
					templateUrl: 'app/project/task-add.html',
					controller: 'addCtrl',
					controllerAs: 'add'
				}
			)
			.when
			(
				'/reminder/:year',
				{
					templateUrl: 'app/project/reminder.html',
					controller: 'reminderCtrl',
					controllerAs: 'rmd'
				}
			)
			.when
			(
				'/reminder/:year/tag/:id',
				{
					templateUrl: 'app/project/task-list.html',
					controller: 'taskCtrl',
					controllerAs: 'task'
				}
			)			
			.when
			(
				'/project',
				{
					templateUrl: 'app/project/project-list.html',
					controller: 'projectCtrl',
					controllerAs: 'pro'
				}
			)
			.when
			(
				'/project/new',
				{
					templateUrl: 'app/project/project-add.html',
					controller: 'proAddCtrl',
					controllerAs: 'proAdd'
				}
			)			
			.when
			(
				'/team',
				{
					templateUrl: 'app/team/department.html',
					controller: 'memberCtrl',
					controllerAs: 'team'
				}
			)
			.when
			(
				'/weekly',
				{
					templateUrl: 'app/calendar/weekly.html',
					controller: 'weeklyCtrl',
					controllerAs: 'w'
				}
			)
			.when
			(
				'/holiday',
				{
	      			redirectTo: function()
	      			{
	      				var today = new Date().getFullYear();
        				return "/holiday/" + today;
      				}
	    		}
			)
			.when
			(
				'/holiday/:year',
				{
					templateUrl: 'app/calendar/holiday.html',
					controller: 'holidayCtrl',
					controllerAs: 'hol'
				}
			)
			.when
			(
				'/calendar/new',
				{
					templateUrl: 'app/calendar/calendar-add.html',
					controller: 'calendarNew',
					controllerAs: 'new'
				}
			)						
			.when
			(
				'/role/new',
				{
					templateUrl: 'app/team/role-add.html',
					controller: 'roleNew',
					controllerAs: 'new'
				}
			)			
			.otherwise
			(
				{
	      			redirectTo: '/'
	    		}
	    	);
		}
	]
);

app.directive
(
	'popup',
	function()
	{
		return {
			restrict: 'E',
			templateUrl: 'lib/template/popup.html'
		};
	}
);

app.filter
(
	'unsafe',
	function($sce)
	{
		return $sce.trustAsHtml;
	}
);

app.filter
(
	'abs',
	function()
	{
		return function(val)
		{
			return Math.abs(val);
		}
	}
);

app.controller
(
	'mainCtrl',
	function($scope, $location, $timeout, $_global)
	{
		$scope.ckb = false;
		$scope.abs = {};
		$scope.viewAbs = {};
		$scope.grant = {};
		//$scope.recent = new Date().getFullYear();
		
		
		$_global.request('json?app=me').success
		(
			function(data)
			{
				$scope.me = data.results;
			    $scope.me.avatar = "temp/team/photo/"+$scope.me.id+".jpg";
			    $scope.abs = {
				    'a1': $_global.abs($scope.me.abs, 6),
				    'a2': $_global.abs($scope.me.abs, 7),
				    'a3': $_global.abs($scope.me.abs, 3)+"/"+30,
				    'a4': $_global.abs($scope.me.abs, 4)+"/"+6,
				    'a5': $_global.abs($scope.me.abs, 5)+"/"+6
			    };

				$scope.grant.all = $_global.grant($scope.me.grant,'GA');
			    $scope.grant.noWeekly = $_global.grant($scope.me.grant,'WN');
			    $scope.grant.pro = $_global.grant($scope.me.grant,'PE');
			    $scope.grant.team = $_global.grant($scope.me.grant,'EA');
			    $scope.grant.calendar = $_global.grant($scope.me.grant,'CC');
			    $scope.grant.task = $_global.grant($scope.me.grant,'TA');
			}
		);

		$_global.request('json?app=year').success
		(
			function(data)
			{
				$scope.years = data.results;
			}
		);

		$_global.request('app/project/json').success
		(
			function(data)
			{
				$scope.dataProject = data.results;
			}
		);

		$_global.request('json?app=project&act=status').success
		(
			function(data)
			{
				$scope.dataProjectStatus = data.results;
			}
		);

		$_global.request('json?app=team').success
		(
			function(data)
			{
				$scope.dataTeam = data.results;
			}
		);

		$_global.request('json?app=customer').success
		(
			function(data)
			{
				$scope.dataCustomer = data.message;
			}
		);

		$scope.popAbs = function(obj,tt,dt,d,lf,lnk)
		{
   			$scope.viewAbs = { 
   				title: tt,
   				link: lnk,
   				left: lf,
   				data: [] 
   			};
			angular.forEach
			(
				obj,
				function(value)
				{
					$scope.viewAbs.data.push
					(
						{
							detail: value[dt],
							date: value[d],
							left: $_global.dayDiff(new Date(), value[d])
						}
					);
				}
			);
		};		

		$scope.changeYear = function(y,loc)
		{
   			$scope.recent = y;
			$location.path('/'+loc+'/'+y);
		};

		$scope.convURL = function(text)
	    {
	    	return text.replace(' ', '_');
	    };

	    $scope.toHTML = function(text)
	    {
	        return text.replace('<br>', '\n');
	    };

	    $scope.objParam = function(obj)
	    {
	    	$scope.x = {};
	    	$scope.x.data = [];
	        angular.forEach
			(
				obj,
				function(value)
				{
					$scope.x.data.push(value);
				}
			);

			return $.param($scope.x);
	    };

	    $scope.isActive = function(view)
	    {
	    	if ( view === $location.path() || view === '/' + $location.path().split('/')[1] )
	    	{
				return true;
	    	}
	    };
		
		$scope.hideMe = function()
	    {
	    	var p = $location.path().replace(/\d+/g, '');

	    	if (
	    			p === '/reminder/new'
	    		||	p === '/project/new'
	    		||	p === '/calendar/new'
	    		||	p === '/role/new'
	    		||	p === '/add'
	    	)
	    	{
	    		$scope.back = false;
	    		$scope.add = false;
	    		return true;
	    	}
	    };

		$scope.goBack = function()
	    {
	    	return history.back();
	    };

		$scope.scrollField = function(id)
		{
			var x = ( id == "alert" ) ? 0 : $("#"+id).offset().top - 70;

			$('html, body').animate
			(
				{
					scrollTop: x
				},
				400
			);
		};
    }
);

app.controller
(
	'menuCtrl',
	function()
	{


	}
);

app.controller
(
	'calendarBox',
	function($scope, $route, $routeParams, $timeout, $filter, $_global)
	{
		var scope = this;
		var d = new Date();
		newDate = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2);
		scope.date = ( $routeParams.date ) ? $routeParams.date : newDate;
		//scope.date = ( $routeParams.date ) ? '&date='+$routeParams.date+'-01' : '';
		//console.log(scope.date);
		scope.edata = {};

		$_global.request('app/calendar/json?view=all&date='+scope.date+'-01').success
		(
			function(data)
			{
				//$scope.status = data.status;
				$scope.calendar = data.results;
				$scope.month_th = data.month[0];
				$scope.month = data.month[1];
				
				var myDate = new Date(data.month[1]);
				var previousMonth = new Date(myDate);
				var nextMonth = new Date(myDate);

				previousMonth.setMonth(myDate.getMonth()-1);
				nextMonth.setMonth(myDate.getMonth()+1);

				scope.prev = $filter('date')(previousMonth, 'yyyy-MM');
				scope.next = $filter('date')(nextMonth, 'yyyy-MM');
			}
		);

		scope.event = {};
		scope.keep = function(obj)
		{
			scope.event = obj;
		};

		scope.formData = {};
		scope.viewData = function(obj,d,id)
		{
			scope.formStatus = obj.status;
			scope.formData = obj.results;
			scope.formData.date = scope.date +'-'+ ("0" + d).slice(-2);
			scope.formData.id = id;
			scope.formData.place = ( scope.formData.place ) ? scope.formData.place : 'บริษัทเอ็กซ์เปิร์ท';
			//console.log(scope.formData);
		};

		scope.add = function()
		{
			//console.log(scope.formData);
			$_global.request('json?app=weekly&act=add', scope.formData).success
  			(
  				function(data)
  				{
					$scope.success = data.success;
					$scope.message = data.message;
  				}
  			);

	  		$scope.show = true;
			$timeout
			(
				function()
				{
					$route.reload();
				},
				4000
			);
		};

		scope.edit = function()
		{
			//console.log(scope.formData);
			$_global.request('json?app=weekly&act=edit', scope.formData).success
  			(
  				function(data)
  				{
					$scope.success = data.success;
					$scope.message = data.message;
  				}
  			);

	  		$scope.show = true;
			$timeout
			(
				function()
				{
					$route.reload();
				},
				4000
			);
		};
    }
);

app.controller
(
	'reminderCtrl',
	function($scope, $routeParams, $http)
	{
		$scope.recent = $routeParams.year;
		var scope = this;
		$http.get('app/project/json?year='+$routeParams.year).success
		(
			function(data)
			{
				scope.data = data.results;
			}
		);

		scope.proType = ["process", "follow", "bid"];
	}
);

app.controller
(
	'taskCtrl',
	function($scope, $route, $routeParams, $http, $timeout, $_global)
	{
		$('.modal').modal('hide');
		$scope.recent = ($routeParams.year=="*") ? new Date().getFullYear() : $routeParams.year;
		$scope.formData = {};
		$scope.filters = { "status" : { "approve" : false } };
		this.ftDefault = { "status" : { "approve" : false } };
		this.ftRecent = { "status" : { "approve" : true } };
		$scope.actClear = false;
		$scope.actApprove = false;
		$scope.taskRecent = false;
		this.title = $routeParams.id.replace('_', ' ');

		$http.get('app/project/json?year='+$routeParams.year+'&id='+$routeParams.id).success
		(
			function(data)
			{
				$scope.data = data.results;
			}
		);

		$scope.select = function(index)
		{
   			return index === $scope.filters.status.approve;
		};

		$scope.commentForm = function(id)
        {
        	var comm = $scope.formData.comm[id];
        	var panel = ".p"+id+" ";

	 		$http
	 		(
	 			{
					method  : 'POST',
	  				url     : 'app/project/comment-process.php',
	  				data    : $.param({ 'id': id, 'comment': comm }),
	  				headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
	  				// set the headers so angular passing info as form data (not request payload)
	 			}
	 		)
	  		.success
	  		(
	  			function(data)
	  			{	
					$(panel+"ul.comment").append('<li>@ฉัน - ' + comm + '</li>');
					$(panel+"input").val('');
					$(panel+"input").focus();
					//$scope.success = data.success;
					//$scope.message = data.message;
	  			}
	  		);
        };
        
		$scope.taskForm = function(id, status, obj)
        {
        	//console.log($scope.grant.task);
        	//console.log($_global.self(obj, $scope.me.nick));
        	if ( $scope.grant.task || ($_global.self(obj, $scope.me.nick) && status!='approve') )
        	{
        		//alert('OK');
	        	var panel = ".p"+id+"";
				$scope.actClear = {};
				$scope.actApprove = {};
				$_global.request('app/project/task-process.php', { 'id': id, 'cmd': status }).success
		  		(
		  			function(data)
		  			{
		  				if ( status == 'clear' )
		  				{
		  					$scope.actClear[id] = true;
		  				}
		  				else if ( status == 'approve' )
		  				{
		  					$scope.actClear[id] = true;
							$scope.actApprove[id] = true;
		  				}
		  				else
		  				{
		  					alert('ERROR');
		  				}
		  				$timeout
						(
							function()
							{
								$route.reload();
							},
							1000
						);
		  			}
		  		);
        	}
        	else
        	{
        		alert('No Permit');
        	}
        };
	}
);

app.controller
(
	'projectCtrl',
	function($scope, $http, $timeout, $window, $_global)
	{
		var scope = this;
		scope.filters = {};
		scope.select = function(index)
		{
   			return index === scope.filters.status;
		};

		scope.formData = {};
		scope.viewData = function(key)
        {
			scope.proMaster = angular.copy($scope.dataProject[key]);
			scope.formData = angular.copy(scope.proMaster);
			//console.log(this.formData);
        }
        scope.resetForm = function()
        {
        	scope.formData = angular.copy(scope.proMaster);
        }
		scope.editForm = function()
        {
			cus = $_global.conv($scope.dataCustomer, 'abb', this.formData.customer);
        	stat = $_global.conv($scope.dataProjectStatus, 'en', this.formData.status);

			scope.editData = {
				'id': this.formData.id,
				'code': this.formData.code,
				'name': this.formData.fullname,
				'nick': this.formData.shortname,
				'link': this.formData.link,
				'cus': cus,
				'stat': stat,
			};

			$_global.request('json?app=project&act=edit', scope.editData).success
  			(
  				function(data)
  				{
					$scope.success = data.success;
					$scope.message = data.message;
  				}
  			);

  			$scope.show = true;
 			$timeout
			(
				function()
				{
					$window.location.reload();
				},
				2000
			);

			delete cus;
			delete stat;
    	};
	}
);

app.controller
(
	'proAddCtrl',
	function($scope, $timeout, $window, $_global)
	{
		$('#add').modal('hide');
        this.formData = {};
        this.addForm = function()
        {
			$_global.request('json?app=project&act=add', this.formData).success
  			(
  				function(data)
  				{
					$scope.success = data.success;
					$scope.message = data.message;
  				}
  			);

	  		$scope.show = true;
  			$scope.scrollField('alert');
			$timeout
			(
				function()
				{
					$window.location.reload();
				},
				2000
			);
    	};
    }
);

app.controller
(
	'memberCtrl',
	function($scope, $window, $timeout, $_global)
	{
		var scope = this;
		scope.name = "ปัจจุบัน";
		scope.filters = {};
		scope.filters.disable = 'N';
		scope.formData = {};
		scope.viewData = function(D,E)
        {
			scope.copyTeam = angular.copy($scope.dataTeam[D].emp[E]);
			scope.formData = angular.copy(scope.copyTeam);
			scope.formData.lead = (scope.formData.level>0) || false;
			scope.formData.leave = (scope.formData.disable=='Y') || false;
			scope.formData.pass = "";
        };
		scope.editForm = function()
        {
        	if ( scope.changeInput && scope.formData.pass.length < 1 )
        	{
				$scope.show = true;
				$scope.message = "Incorrect password";
				$timeout
				(
					function()
					{
						$scope.show = false;
					},
					2000
				);
        	}
        	else
        	{
				scope.editData = {
					'id': scope.formData.id,
					'dept': scope.formData.dept,
					'pos': scope.formData.pos,
					'phone': scope.formData.phone,
					'desk': scope.formData.desk,
					'lead': scope.formData.lead,
					'leave': scope.formData.leave,
					'pass': scope.formData.pass
				};

				//console.log(this.editData);
		
				$_global.request('json?app=team&act=edit', scope.editData).success
	  			(
	  				function(data)
	  				{
						$scope.success = data.success;
						$scope.message = data.message;
	  				}
	  			);

	  			$scope.show = true;
	 			$timeout
				(
					function()
					{
						$window.location.reload();
					},
					4000
				);
			}
    	};
	}
);

app.controller
(
	'weeklyCtrl',
	function($scope, $routeParams, $filter, $window, $_global)
	{
		var scope = this;
		var d = new Date();
  		newDate = d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2);
		scope.date = ( $routeParams.date ) ? $routeParams.date : newDate;

		var myDate = new Date(scope.date);
		var previousMonth = new Date(myDate);
		var nextMonth = new Date(myDate);
		previousMonth.setMonth(myDate.getMonth()-1);
		nextMonth.setMonth(myDate.getMonth()+1);
		scope.prev = $filter('date')(previousMonth, 'yyyy-MM');
		scope.next = $filter('date')(nextMonth, 'yyyy-MM');
		scope.month = $filter('date')(myDate, 'MMMM yyyy');
        $_global.request('json?app=calendar&view=weekly&date='+scope.date).success
  		(
  			function(data)
  			{
				scope.success = data.success;
				scope.results = data.results;
  			}
  		);

  		scope.url = function(d,e,n,p,dn,abb,date,w)
  		{
  			return "report?dept="+d+"&emp="+e+"&name="+n+"&pos="+p+"&dn="+dn+"&abb="+abb+"&date="+date+"&week="+w;
  		}
    }
);

app.controller
(
	'holidayCtrl',
	function($scope, $routeParams, $filter, $_global)
	{
		$scope.recent = $routeParams.year;
		var scope = this;

        $_global.request('json?app=calendar&view=holiday&year='+$routeParams.year).success
  		(
  			function(data)
  			{
				//$scope.success = data.success;
				scope.results = data.results;
  			}
  		);

  		this.pastDate = function(d)
		{
   			return d < $filter('date')(new Date(), 'yyyy-MM-dd');
		};
    }
);

app.controller
(
	'roleNew',
	function($scope, $window, $timeout, $_global)
	{
		var scope = this;
		scope.formData = {};
        scope.processForm = function()
        {
			if ( angular.isUndefined(scope.formData.team) )
        	{
				scope.teamError = true;
				$scope.scrollField('inp-team');
        	}        	
        	else
        	{
        		//console.log(scope.formData);
				$_global.request('json?app=role&act=add', scope.formData).success
	  			(
	  				function(data)
	  				{
						$scope.success = data.success;
						$scope.message = data.message;
	  				}
	  			);

				$scope.scrollField('alert');
	  			$scope.show = true;
	 			$timeout
				(
					function()
					{
						$window.location.reload();
					},
					4000
				);
  			}
    	};
    }
);

app.controller
(
	'calendarNew',
	function($scope, $routeParams, $window, $timeout, $_global)
	{
		var scope = this;
    	$('.picker').datepicker
		(
			{
				language: "th",
				todayHighlight: true
			}
		)
		.on
		(
			'changeDate',
			function(e)
			{
				//$('#inp-date').val(e.format('yyyy-mm-dd'));
				scope.formData.date = e.format('yyyy-mm-dd');
				scope.dateError = false;
			}
		);

		scope.formData = {};
		scope.add = $routeParams.add;

		if (scope.add=='engage')
		{
			scope.title = "นัดหมาย";
			scope.formData.type = 8;
		}
		else if (scope.add=='abs')
		{
			scope.title = "ลางาน";
			scope.formData.type = 3;
		}
		else if (scope.add=='holiday')
		{
			scope.title = "สร้างปฏิทิน";
			scope.formData.type = 1;
		}
		else
		{
			//nothing
		}

        scope.processForm = function()
        {
			if ( angular.isUndefined(scope.formData.date) )
        	{
				scope.dateError = true;
				$scope.scrollField('inp-date');
        	}
			else if ( angular.isUndefined(scope.formData.team) && scope.formData.type > 2 )
        	{
				scope.teamError = true;
				$scope.scrollField('inp-team');
        	}        	
        	else
        	{
        		//console.log(scope.formData);
				$_global.request('json?app=calendar&act=add', scope.formData).success
	  			(
	  				function(data)
	  				{
						$scope.success = data.success;
						$scope.message = data.message;
	  				}
	  			);

				$scope.scrollField('alert');
	  			$scope.show = true;
	 			$timeout
				(
					function()
					{
						$window.location.reload();
					},
					4000
				);
  			}
    	};
    }
);

app.controller
(
	'addCtrl',
	function($scope, $http, $route, $timeout)
	{
    	$('#add').modal('hide');
    	$('.picker').datepicker
		(
			{
				language: "th",
				todayHighlight: true
			}
		)
		.on
		(
			'changeDate',
			function(e)
			{
				//$('#inp-date').val(e.format('yyyy-mm-dd'));
				$scope.formData.date = e.format('yyyy-mm-dd');
				$scope.dateshow = false;
			}
		);

        $scope.formData = {};
		$scope.formData.pro = [];
		$scope.formData.emp = [];
		$scope.formData.during = 3;
		$scope.proTakeout = ['finish', 'cancle'];
		this.proExcept = function(i)
		{
   			return ($scope.proTakeout.indexOf(i.status) === -1);
		};
		$scope.teamTakeout = ['Y'];
		this.teamExcept = function(i)
		{
   			return ($scope.teamTakeout.indexOf(i.disable) === -1);
		};
		$scope.togglePro = function(txt)
		{
			var idx = $scope.formData.pro.indexOf(txt);
			if (idx > -1)
			{
				$scope.formData.pro.splice(idx, 1);
			}
			else
			{
				$scope.formData.pro.push(txt);
				$scope.proshow = false;
			}
		};
		$scope.toggleEmp = function(txt)
		{
			var idx = $scope.formData.emp.indexOf(txt);
			if (idx > -1)
			{
				$scope.formData.emp.splice(idx, 1);
			}
			else
			{
				$scope.formData.emp.push(txt);
				$scope.empshow = false;
			}
		};
        $scope.processForm = function()
        {
			if ( $scope.formData.pro.length < 1 )
        	{
				$scope.proshow = true;
				$scope.scrollField('inp-pro');
        	}
        	else if ( $scope.formData.emp.length < 1 )
        	{
				$scope.empshow = true;
				$scope.scrollField('inp-emp');
        	}
        	else if ( angular.isUndefined($scope.formData.date) )
        	{
				$scope.dateshow = true;
				$scope.scrollField('inp-date');
        	}
        	else
        	{
	 			$http
	 			(
	 				{
						method  : 'POST',
	  					url     : 'app/project/process.php',
	  					data    : $.param($scope.formData),  // pass in data as strings
	  					headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
	 				}
	 			)
	  			.success
	  			(
	  				function(data)
	  				{	
	  					$('#inp-detail').val('');
						$('#inp-detail').focus();
						$scope.success = data.success;
						$scope.message = data.message;
	  				}
	  			)
	  			.error
	  			(
	  				function(data, status)
	  				{	
			        	$scope.success = false;
			        	$scope.message = status;
	  				}
	  			)		
				
				$scope.scrollField('alert');
	  			$scope.show = true;
				$timeout
				(
					function()
					{
						//$scope.show = false;
						$route.reload();
					},
					2000
				);
  			}
    	};
    }
);