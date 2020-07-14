// var nodes = [
// 	{
// 		id: "1",
// 		title: "Masukkan Jabatan Atasan Anda",
// 		me: ""
// 	},
// 	{
// 		id: "2",
// 		pid: "1",
// 		title: "Masukkan Jabatan",
// 		me: ""
// 	},
// 	{
// 		id: "3",
// 		pid: "1",
// 		title: "Masukkan Jabatan",
// 		me: ""
// 	},
// 	{
// 		id: "4",
// 		pid: "1",
// 		title: "Masukkan Jabatan",
// 		me: "dasdas"
// 	},
// 	{
// 		id: "5",
// 		pid: "2",
// 		title: "Masukkan Jabatan",
// 		me: "",
// 		tags: "asistant"
// 	}
// ];

// for (var i = 0; i < nodes.length; i++) {
// 	var node = nodes[i];
// 	if (node.me < 1) {
// 		node.tags = [];
// 	} else {
// 		node.tags = ["me"];
// 	}
// }

//setting of OrgChart
// var chart = new OrgChart(document.getElementById("tree"), {
// 	template: "ana",
// 	layout: BALKANGraph.mixed,
// 	mouseScrool: OrgChart.action.none,
// 	enableDragDrop: false,
// 	enableSearch: false,
// 	menu: {
// 		pdf: {
// 			text: "Export PDF"
// 		}
// 	},
// 	nodeMenu: {
// 		edit: {
// 			text: "Edit"
// 		},
// 		add: {
// 			text: "Add"
// 		},
// 		remove: {
// 			text: "Remove"
// 		}
// 	},
// 	nodeBinding: {
// 		field_0: "title",
// 		field_1: "me"
// 	},
// 	nodes: nodes
// });

// Ryu Development Area

// var hierarchy = $('#tree').OrgChart('getHierarchy');
// console.log(hierarchy);

// $('#simpan-chart').on('click', function () {
// 	$('pre').empty();
// 		// console.log("kamu berhasil");
// 		var hierarchy = $('#tree').OrgChart('getHierarchy');
// 		// var tree = JSON.stringify(hierarchy, null, 2);

// 		console.log(hierarchy);
// });



// $('#btn-export-hier').on('click', function () {
// 	$('pre').empty();
// 		var hierarchy = $('#chart-container').orgchart('getHierarchy');
// 		var tree = JSON.stringify(hierarchy, null, 2);
// 		$.ajax({
// 			type: "POST",
// 			url: "http://mySite/SaveData?tree=" + tree,
// 			success: function (data) {
// 			},
// 			dataType: "json",
// 			traditional: true
// 		});
// });

// start orgchart

(function($){

	$(function() {
		
		//dummy datasource
		// var datasource = {
		// 	'id': '1',
		// 	'name': 'Lao Lao',
		// 	'title': 'general manager',
		// 	'children': [
		// 	  	{ 'id': '2', 'name': 'Bo Miao', 'title': 'department manager' },
		// 	  	{ 'id': '3', 'name': 'Su Miao', 'title': 'department manager',
		// 			'children': [
		// 				{ 'id': '4', 'name': 'Tie Hua', 'title': 'senior engineer' },
		// 				{ 'id': '5', 'name': 'Hei Hei', 'title': 'senior engineer',
		// 					'children': [
		// 						{ 'id': '6', 'name': 'Pang Pang', 'title': 'engineer' },
		// 						{ 'id': '7', 'name': 'Xiang Xiang', 'title': 'UE engineer' }
		// 					]
		// 				}
		// 			]
		// 	   	},
		// 	   	{ 'id': '8', 'name': 'Hong Miao', 'title': 'department manager' },
		// 	   	{ 'id': '9', 'name': 'Chun Miao', 'title': 'department manager' }
		// 	]
		// };
  

//ORGCHART FROM DABENG
	  var getId = function() {
		return (new Date().getTime()) * 1000 + Math.floor(Math.random() * 1001);
	  };

	  
	  function isEven(num){
		  if(num % 2 === 0){
			  return true;
		  } else {
			  return false;
		  }
	  };

	//   alert(isEven(15));

	   // datasource_assistant -> this variable from jobs/index.html
	   // datasource -> this variable from jobs/index.html

	//    console.log(datasource_assistant[0].position_name);
	

	if(atasan == 2){ //buat yang punya 2 atasan
		if(assistant_atasan1 == 1){ //jika punya atasan 1 tampilkan horizontal
			var oc = $('#chart-container').orgchart({
				data : datasource,
				zoom: true,
				pan: true,
				// chartClass: 'edit-state',
				// 'exportButton': true,
				// 'exportFilename': 'OrgChart',
				// verticalLevel: 3, // this method cannot compatible with the edit-state
				// draggable: true,
				// parentNodeSymbol: 'fa-th',
				nodeTitle: 'position_name',
				createNode: function($node, data) {
					$.each(datasource_assistant1, function(i, item) {
						// console.log(item.atasan_assistant);

						if (data.position_name === item.atasan_assistant){//cek apa posistion name
							$(document).ready(function() {
								$('.downLine').css({"height": "110px"});
							});
							if (i>1){//assistant lebih dari 2
								$(document).ready(function() {
									$('.downLine').css({"height": "220px"});
								});
								var node_location = 200;
							} else {//tidak?
								var node_location = 100;
							}
							
							if(isEven(i) == true){ //cek jika nilai i odd atau even, gunanya buat nodenya bisa tampil terpisah jika lebih dari satu assistant
								var node_position  = 140;
								var node_connector = -65;
							} else {
								var node_position  = -123;
								var node_connector = 130;
							}
		
							if("className" in item){ //jika ada array key classname di position // posisi dia assistant
								var assistantNode = '<div class="assistant-node my-position" style="left: ' + node_position + 'px; top: ' + node_location + 'px;"><div class="connector" style="left: ' + node_connector + 'px;"/></div><div class="title"><i class="fa fa-user-circle-o symbol"></i>' + item.position_name + '</div><i class="edge verticalEdge bottomEdge fa"></i>';
							}else{
								var assistantNode = '<div class="assistant-node" style="left: ' + node_position + 'px; top: ' + node_location + 'px;"><div class="connector" style="left: ' + node_connector + 'px;"/></div><div class="title"><i class="fa fa-user-circle-o symbol"></i>' + item.position_name + '</div><i class="edge verticalEdge bottomEdge fa"></i>';
							}
		
							$node.append(assistantNode);
						}
					});
					$.each(datasource_assistant2, function(i, item) {
						console.log(item.atasan_assistant2);
		
						if (data.position_name === item.atasan_assistant){//cek apa posistion name
							if (i>1){//assistant lebih dari 2
								$(document).ready(function() {
									$('.downLine').css({"height": "220px"});
								});
								var node_location = 200;
							} else {//tidak?
								var node_location = 100;
							}
							
							if(isEven(i) == true){ //cek jika nilai i odd atau even, gunanya buat nodenya bisa tampil terpisah jika lebih dari satu assistant
								var node_position  = 140;
								var node_connector = -65;
							} else {
								var node_position  = -123;
								var node_connector = 130;
							}
		
							if("className" in item){ //jika ada array key classname di position // posisi dia assistant
								var assistantNode = '<div class="assistant-node my-position" style="left: ' + node_position + 'px; top: ' + node_location + 'px;"><div class="connector" style="left: ' + node_connector + 'px;"/></div><div class="title"><i class="fa fa-user-circle-o symbol"></i>' + item.position_name + '</div><i class="edge verticalEdge bottomEdge fa"></i>';
							}else{
								var assistantNode = '<div class="assistant-node" style="left: ' + node_position + 'px; top: ' + node_location + 'px;"><div class="connector" style="left: ' + node_connector + 'px;"/></div><div class="title"><i class="fa fa-user-circle-o symbol"></i>' + item.position_name + '</div><i class="edge verticalEdge bottomEdge fa"></i>';
							}
		
							$node.append(assistantNode);
						}
						
					});
				//  if (data.title === "general manager") {
				// 		var assistantNode =
				// 		  '<div class="assistant-node"><div class="connector"/><div class="title"><i class="fa fa-user-circle-o symbol"></i>Dan Dan</div><div class="content">general office</div><i class="edge verticalEdge bottomEdge fa"></i></div>';
				// 		$node.append(assistantNode);
				// 	  }
				  $node[0].id = getId();
				}
			});
		} else { //ika ga punya atasan 1 level 3nya tampilkan vertikal
			var oc = $('#chart-container').orgchart({
				data : datasource,
				zoom: true,
				pan: true,
				// chartClass: 'edit-state',
				// 'exportButton': true,
				// 'exportFilename': 'OrgChart',
				verticalLevel: 3, // this method cannot compatible with the edit-state
				// draggable: true,
				parentNodeSymbol: 'fa-th-large',
				nodeTitle: 'position_name',
				createNode: function($node, data) {
					$.each(datasource_assistant2, function(i, item) {
						// console.log(item.atasan_assistant);
		
						if (data.position_name === item.atasan_assistant){//cek apa posistion name
							$(document).ready(function() {
								$('.downLine').css({"height": "110px"});
							});
							if (i>1){//assistant lebih dari 2
								$(document).ready(function() {
									$('.downLine').css({"height": "220px"});
								});
								var node_location = 200;
							} else {//tidak?
								var node_location = 100;
							}
							
							if(isEven(i) == true){ //cek jika nilai i odd atau even, gunanya buat nodenya bisa tampil terpisah jika lebih dari satu assistant
								var node_position  = 140;
								var node_connector = -65;
							} else {
								var node_position  = -123;
								var node_connector = 130;
							}
		
							if("className" in item){ //jika ada array key classname di position // posisi dia assistant
								var assistantNode = '<div class="assistant-node my-position" style="left: ' + node_position + 'px; top: ' + node_location + 'px;"><div class="connector" style="left: ' + node_connector + 'px;"/></div><div class="title"><i class="fa fa-user-circle-o symbol"></i>' + item.position_name + '</div><i class="edge verticalEdge bottomEdge fa"></i>';
							}else{
								var assistantNode = '<div class="assistant-node" style="left: ' + node_position + 'px; top: ' + node_location + 'px;"><div class="connector" style="left: ' + node_connector + 'px;"/></div><div class="title"><i class="fa fa-user-circle-o symbol"></i>' + item.position_name + '</div><i class="edge verticalEdge bottomEdge fa"></i>';
							}
		
		
							$node.append(assistantNode);
						}
					});
				//  if (data.title === "general manager") {
				// 		var assistantNode =
				// 		  '<div class="assistant-node"><div class="connector"/><div class="title"><i class="fa fa-user-circle-o symbol"></i>Dan Dan</div><div class="content">general office</div><i class="edge verticalEdge bottomEdge fa"></i></div>';
				// 		$node.append(assistantNode);
				// 	  }
				  $node[0].id = getId();
				}
			});
		}
	} else if(atasan == 1) { //buat yang punya 1 atasan
		var oc = $('#chart-container').orgchart({
			data : datasource,
			zoom: true,
			pan: true,
			// chartClass: 'edit-state',
			// 'exportButton': true,
			// 'exportFilename': 'OrgChart',
			// verticalLevel: 3, // this method cannot compatible with the edit-state
			// draggable: true,
			parentNodeSymbol: 'fa-th-large',
			nodeTitle: 'position_name',
			createNode: function($node, data) {
				$.each(datasource_assistant1, function(i, item) {
					// console.log(item.atasan_assistant);
	
					if (data.position_name === item.atasan_assistant){//cek apa posistion name
						$(document).ready(function() {
							$('.downLine').css({"height": "110px"});
						});
						if (i>1){//assistant lebih dari 2
							$(document).ready(function() {
								$('.downLine').css({"height": "220px"});
							});
							var node_location = 200;
						} else {//tidak?
							var node_location = 100;
						}
						
						if(isEven(i) == true){ //cek jika nilai i odd atau even, gunanya buat nodenya bisa tampil terpisah jika lebih dari satu assistant
							var node_position  = 140;
							var node_connector = -65;
						} else {
							var node_position  = -123;
							var node_connector = 130;
						}
	
						if("className" in item){ //jika ada array key classname di position // posisi dia assistant
							var assistantNode = '<div class="assistant-node my-position" style="left: ' + node_position + 'px; top: ' + node_location + 'px;"><div class="connector" style="left: ' + node_connector + 'px;"/></div><div class="title"><i class="fa fa-user-circle-o symbol"></i>' + item.position_name + '</div><i class="edge verticalEdge bottomEdge fa"></i>';
						}else{
							var assistantNode = '<div class="assistant-node" style="left: ' + node_position + 'px; top: ' + node_location + 'px;"><div class="connector" style="left: ' + node_connector + 'px;"/></div><div class="title"><i class="fa fa-user-circle-o symbol"></i>' + item.position_name + '</div><i class="edge verticalEdge bottomEdge fa"></i>';
						}
	
	
						$node.append(assistantNode);
					}
				});
			//  if (data.title === "general manager") {
			// 		var assistantNode =
			// 		  '<div class="assistant-node"><div class="connector"/><div class="title"><i class="fa fa-user-circle-o symbol"></i>Dan Dan</div><div class="content">general office</div><i class="edge verticalEdge bottomEdge fa"></i></div>';
			// 		$node.append(assistantNode);
			// 	  }
			  $node[0].id = getId();
			}
		});
	} else { //buat yang ga punya atasan
		//do something
	}
  
	  oc.$chartContainer.on('click', '.node', function() {
		var $this = $(this);
		$('#selected-node').val($this.find('.title').text()).data('node', $this);
	  });
  
	  oc.$chartContainer.on('click', '.orgchart', function(event) {
		if (!$(event.target).closest('.node').length) {
		  $('#selected-node').val('');
		}
	  });

	  oc.$chart.on('nodedrop.orgchart', function(event, extraParams) {
		  console.log('draggedNode:' + extraParams.draggedNode.children('.title').text()
		  	+ ', dragZone:' + extraParams.dragZone.children('.title').text()
		    + ', dropZone:' + extraParams.dropZone.children('.title').text()
		  );
	  });
  
	  $('input[name="chart-state"]').on('click', function() {
		$('.orgchart').toggleClass('edit-state', this.value !== 'view');
		$('#edit-panel').toggleClass('edit-state', this.value === 'view');
		if ($(this).val() === 'edit') {
		  $('.orgchart').find('tr').removeClass('hidden')
			.find('td').removeClass('hidden')
			.find('.node').removeClass('slide-up slide-down slide-right slide-left');
		} else {
		  $('#btn-reset').trigger('click');
		}
	  });
  
	  $('input[name="node-type"]').on('click', function() {
		var $this = $(this);
		if ($this.val() === 'parent') {
		  $('#edit-panel').addClass('edit-parent-node');
		  $('#new-nodelist').children(':gt(0)').remove();
		} else {
		  $('#edit-panel').removeClass('edit-parent-node');
		}
	  });
  
	  $('#btn-add-input').on('click', function() {
		$('#new-nodelist').append('<li><input type="text" class="new-node"></li>');
	  });
  
	  $('#btn-remove-input').on('click', function() {
		var inputs = $('#new-nodelist').children('li');
		if (inputs.length > 1) {
		  inputs.last().remove();
		}
	  });
  
	  $('#btn-add-nodes').on('click', function() {
		var $chartContainer = $('#chart-container');
		var nodeVals = [];
		$('#new-nodelist').find('.new-node').each(function(index, item) {
		  var validVal = item.value.trim();
		  if (validVal.length) {
			nodeVals.push(validVal);
		  }
		});
		var $node = $('#selected-node').data('node');
		if (!nodeVals.length) {
		  alert('Please input value for new node');
		  return;
		}
		var nodeType = $('input[name="node-type"]:checked');
		if (!nodeType.length) {
		  alert('Please select a node type');
		  return;
		}
		if (nodeType.val() !== 'parent' && !$('.orgchart').length) {
		  alert('Please creat the root node firstly when you want to build up the orgchart from the scratch');
		  return;
		}
		if (nodeType.val() !== 'parent' && !$node) {
		  alert('Please select one node in orgchart');
		  return;
		}
		if (nodeType.val() === 'parent') {
		  if (!$chartContainer.children('.orgchart').length) {// if the original chart has been deleted
			oc = $chartContainer.orgchart({
			  'data' : { 'name': nodeVals[0] },
			  'exportButton': true,
			  'exportFilename': 'SportsChart',
			  'parentNodeSymbol': 'fa-th-large',
			  'createNode': function($node, data) {
				$node[0].id = getId();
			  }
			});
			oc.$chart.addClass('view-state');
		  } else {
			oc.addParent($chartContainer.find('.node:first'), { 'name': nodeVals[0], 'id': getId() });
		  }
		} else if (nodeType.val() === 'siblings') {
		  if ($node[0].id === oc.$chart.find('.node:first')[0].id) {
			alert('You are not allowed to directly add sibling nodes to root node');
			return;
		  }
		  oc.addSiblings($node, nodeVals.map(function (item) {
			  return { 'name': item, 'relationship': '110', 'id': getId() };
			}));
		} else {
		  var hasChild = $node.parent().attr('colspan') > 0 ? true : false;
		  if (!hasChild) {
			var rel = nodeVals.length > 1 ? '110' : '100';
			oc.addChildren($node, nodeVals.map(function (item) {
				return { 'name': item, 'relationship': rel, 'id': getId() };
			  }));
		  } else {
			oc.addSiblings($node.closest('tr').siblings('.nodes').find('.node:first'), nodeVals.map(function (item) {
				return { 'name': item, 'relationship': '110', 'id': getId() };
			  }));
		  }
		}
	  });
  
	  $('#btn-delete-nodes').on('click', function() {
		var $node = $('#selected-node').data('node');
		if (!$node) {
		  alert('Please select one node in orgchart');
		  return;
		} else if ($node[0] === $('.orgchart').find('.node:first')[0]) {
		  if (!window.confirm('Are you sure you want to delete the whole chart?')) {
			return;
		  }
		}
		oc.removeNodes($node);
		$('#selected-node').val('').data('node', null);
	  });
  
	  $('#btn-reset').on('click', function() {
		$('.orgchart').find('.focused').removeClass('focused');
		$('#selected-node').val('');
		$('#new-nodelist').find('input:first').val('').parent().siblings().remove();
		$('#node-type-panel').find('input').prop('checked', false);
	  });

	  $('#simpan-chart').on('click', function () {
		if (!$("pre").length) {
			var hierarchy = oc.getHierarchy();
			var datasave = JSON.stringify(hierarchy, null, 2);
			console.log(datasave);
		}
		
		// $('pre').empty();
		// 	// console.log("kamu berhasil");
		// 	var hierarchy = $('#tree').orgchart('getHierarchy');
		// 	// var tree = JSON.stringify(hierarchy, null, 2);

		// 	console.log(hierarchy);
		});
  
	});
  
  })(jQuery);

// end orgchart

// chart.editUI.on('imageuploaded', function (sender, file, input){
// 	var formData = new FormData();
// 	formData.append('file', file);
// 	$.ajax({
// 		type: "POST",
// 		url: "",
// 		data: formData,
// 		dataType: 'json',
// 		contentType: false,
// 		processData: false,
// 		success: function(data){
// 			input.value = data.url;
// 		},
// 		error: function (error) {
// 			alert(error);
// 		}
// 	});
// });

// console.log(formData);