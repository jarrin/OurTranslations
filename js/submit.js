
var submit = 
{
	processBar: $("<div>").addClass("processbar").append($("<div>")),
	ready: function()
	{
		$(".dropzone").filedrop({
			callback: function(result, files)
			{
				//Update Queue
				submit.uploadEvents.addQueue(files);
			},
			dragenter: submit.dragEvents.enter,
			dragleave: submit.dragEvents.leave
		});
		$("#fake-browse").click(function(){ $('#browse-files').click(); });
		$('#browse-files').change(function(){
			submit.uploadEvents.addQueue($(this).prop("files"));
		});
		$("#process").click(submit.uploadEvents.start);
		submit.getSessionID();
	},
	getSessionID: function()
	{
		$.ajax({
			url: 'ajax/submit/getSessionID',
			success: function(data)
			{
				submit.sessionID = data.id;
			}
		});
	},
	dragEvents:
	{
		enter: function()
		{
			$("#droptext .normal").hide();
			$("#droptext .hover").show();
		},
		leave: function()
		{
			$("#droptext .normal").show();
			$("#droptext .hover").hide();
		},

	},
	uploadEvents:
	{
		error: function(file)
		{
			
		},
		addQueue: function(files)
		{
			var full = false;
			var c = 1;
			var d = 4000;
			$(files).each(function(i, file)
			{
				if(submit.queue.length+1 <= 8)
				{
					var color = (c %2) ? "black" : "white";
					var tr = $("<tr>").append(
						$("<td>").addClass("file-name").append($("<div>").html(file.name)), 
						$("<td>").addClass("file-size").html(formatFileSize(file.size)), 
						$("<td>").addClass("file-type").html("pdf"),
						$("<td>").addClass("file-state").html("Ready"),
						$("<td>").attr("rel", submit.queue.length).addClass("file-delete").html('<img src="images/delete_' + color + '.svg" />').click(function(){
							var id = $(this).attr("rel");
							var n = noty({
								text: 'Do you want to remove this file? <br><b>'+ file.name +'</b>',
								type: 'warning',
								buttons: 
								[
									{addClass: 'btn btn-danger', text: 'Ok', onClick: function(){ submit.uploadEvents.remove(id); n.close(); }},
									{addClass: 'btn btn-info', text: 'Cancel', onClick: function(){ n.close(); }}
								],
								callback: {	onShow : null }
							});
						})
					);

					var passed = false;
					console.log(file.type);
					if(file.type == "") //no mime-typd, look for extension insteds
					{
						
						var ext = file.name.split(".");
						ext = ext[1];
						if(submit.allowedExt.indexOf(ext) !== -1) passed = true;
					}
					else if(submit.allowedMime.indexOf(file.type) !== -1) passed = true;


					if(!passed)
					{
						$(tr).addClass("wrong-file-type");
						$(tr).find("td.file-delete").html("");
						$(tr).find("td.file-type").html("Not suppoted");
						$(tr).find("td.file-state").html("Deleted");
						setTimeout(function(){
							$(tr).remove();
							submit.uploadEvents.resetDeleteButtons();
						}, d);
						d += 1000;
					}
					else
					{

						if(submit.queue.length > 0) $(tr).css("display", "none");
						$("#file-queue").append(tr);
						
						if(submit.queue.length == 0)
						{
							$("#upload-icon").animate({"opacity": .1}, 1000);
							$("#header, #file-queue").fadeIn(1000);

						} else $("#file-queue tr").show();
						
						 submit.queue.push(file);
						$("#counter .number").html(submit.queue.length);
					}
				} 
				else full = true;
				c++;
			});

			if(full)
			{ 
				noty({text: 'Limit of files reached', type: "warning"});
				$("#counter .number").css("color", "red");
			}
			if(submit.queue.length)
			{
				$("#process").removeAttr("disabled");
			} 
			else
			{
				$("#process").attr("disabled", "disabled");
			}
		},
		resetDeleteButtons: function()
		{
			$("#file-queue tr:odd > td > img").attr("src", "images/delete_black.svg");
			$("#file-queue tr:even > td > img").attr("src", "images/delete_white.svg");
		},
		remove: function(id)
		{
			id = parseInt(id);
			submit.queue.splice(id, 1);
			$("#file-queue tr").get(id+1).remove();
			//reindexing
			$("#file-queue tr .file-delete").each(function(i, el){
				$(el).attr("rel", i);
			});
			submit.uploadEvents.resetDeleteButtons();
		},
		start: function()
		{
			$("#file-queue tr:gt(0) .file-delete").css("cursor", "default").html("").unbind("click");
			$(submit.queue).each(function(i, file){
				var formData = new FormData();  
        		formData.append("file", file);
        		formData.append("sessionID", submit.sessionID); 
				$.ajax({
					url: 'ajax/submit/upload',
					data: formData,
					processData: false,
					contentType: false,
					success: function(data){
						console.log(data);
					},
			   		beforeSend: function(e)
			   		{
			   			submit.uploadEvents.monitorProcces();
			   		}
				});
			});
		},
		monitorProcces: function()
		{
			submit.uploadEvents.processInterval = setInterval(function()
			{
				$.ajax({
					url: 'ajax/submit/uploadProcces',
					data: { id :  submit.sessionID},
					success: function(data)
					{
						;
					}
				});
			}, 1000);
		}
	},
	processInterval: null,
	updateSteps: function(activeStep)
	{

	},

	allowedMime:
	[
		"application/msword",
		"application/pdf",
		"text/plain",
		"application/x-msdownload"
	],
	allowedExt:
	[
		"php",
		"txt",
		"json",
		"zip",
		"exe"
	],
	queue: [],
};