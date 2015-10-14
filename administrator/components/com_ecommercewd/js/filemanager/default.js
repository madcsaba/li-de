 /**
 * package E-Commerce WD
 * author Web-Dorado
 * copyright (C) 2014 Web-Dorado. All rights reserved.
 * license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

////////////////////////////////////////////////////////////////////////////////////////
// Events                                                                             //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constants                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Variables                                                                          //
////////////////////////////////////////////////////////////////////////////////////////

var keyFileSelected;
var filesSelected;
var dragFiles;
var isUploading;

////////////////////////////////////////////////////////////////////////////////////////
// Constructor                                                                        //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////

jQuery(document).ready(function () {
    filesSelected = [];
    dragFiles = [];

    //file manager under system messages
    jQuery("#wrapper").css("top", jQuery("#system-message-container").css("height"));
    jQuery(window).resize(function () {
        jQuery("#container").css("top", jQuery("#system-message-container").css("height"));
    });

    isUploading = false;

    jQuery("#uploader").css("display", "none");
    jQuery("#uploader_progress_bar").css("display", "none");

    //decrease explorer header width by scroller width

    jQuery(".scrollbar_filler").css("width", getScrollBarWidth() + "px");

    // for uploader

    jQuery("#jQueryUploader").fileupload({

        dataType: "json",
        dropZone: jQuery("#uploader_hitter"),
        submit: function (event, data) {
            jQuery("#uploader_progress_text").removeClass("uploader_text");
            isUploading = true;
            jQuery("#uploader_progress_bar").fadeIn();
        },

        progressall: function (event, data) {

            var progress = parseInt(data.loaded / data.total * 100, 10);
            jQuery("#uploader_progress_text").text("Progress " + progress + "%");
            jQuery("#uploader_progress div div").css({width: progress + "%"});

            if (data.loaded == data.total) {

                isUploading = false;
                jQuery("#uploader_progress_bar").fadeOut(function () {
                    jQuery("#uploader_progress_text").text(messageFilesUploadComplete);
                    jQuery("#uploader_progress_text").addClass("uploader_text");

                });
            }
        },

        stop: function (event, data) {
            hideUploader();
        },

        done: function (event, data) {
			//console.log(data);
            jQuery.each(data.result.files, function (index, file) {
                if (file.error) {
                    alert(errorLoadingFile + ' :: ' + file.error);
                }
                if (file.error) {
                    jQuery("#uploaded_files ul").append(jQuery("<li class=uploaded_item_failed>" + FM_MSG_UPLOAD_FAILED + " :: " + file.error + "</li>"));

                } else {
                    jQuery("#uploaded_files ul").append(jQuery("<li class=uploaded_item>" + file.name + " (" + FM_MSG_UPLOADED + ")" + "</li>"));
                }
            });
        }
    });

    jQuery(document).keydown(function(event) {
        onKeyDown(event);

    });

});

////////////////////////////////////////////////////////////////////////////////////////
// Getters & Setters                                                                  //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Private Methods                                                                    //
////////////////////////////////////////////////////////////////////////////////////////

function hideUploader() {

    if ((isUploading == false) || (confirm(warningCancelUploads) == true)) {
        submit("", null, null, null, null, null, null, null, null, null, null);
    }

}

function getClipboardFiles() {
    return jQuery("form[name=adminForm]").find("input[name=clipboard_file]").val();
}

function submit(task, sortBy, sortOrder, itemsView, destDir, fileNewName, newDirName, clipboardTask, clipboardFiles, clipboardSrc, clipboardDest) {
    fileNames = filesSelected.join("**#**");
	
    switch (task) {

        case "rename_item":
            destDir = dir;
            newDirName = "";
            clipboardTask = ""
            clipboardDest = "";
            break;

        case "remove_items":
            destDir = dir;
            fileNewName = "";
            newDirName = "";
            clipboardTask = ""
            clipboardDest = "";
            break;

        case "make_dir":
            destDir = dir;
            fileNewName = "";
            clipboardTask = ""
            clipboardDest = "";
            break;

        case "paste_items":
            destDir = dir;
            fileNewName = "";
            newDirName = "";
            break;

        default:
            task = "";
            break;
    }
	
    jQuery("form[name=adminForm]").find("input[name=task]").val(task);

    if (sortBy != null) {
        jQuery("form[name=adminForm]").find("input[name=sort_by]").val(sortBy);
    }

    if (sortOrder != null) {
        jQuery("form[name=adminForm]").find("input[name=sort_order]").val(sortOrder);
    }

    if (itemsView != null) {
        jQuery("form[name=adminForm]").find("input[name=items_view]").val(itemsView);
    }

    if (destDir != null) {
        jQuery("form[name=adminForm]").find("input[name=dir]").val(destDir);
    }

    if (fileNames != null) {
        jQuery("form[name=adminForm]").find("input[name=file_names]").val(fileNames);
    }

    if (fileNewName != null) {
        jQuery("form[name=adminForm]").find("input[name=file_new_name]").val(fileNewName);
    }

    if (newDirName != null) {
        jQuery("form[name=adminForm]").find("input[name=new_dir_name]").val(newDirName);
    }

    if (clipboardTask != null) {
        jQuery("form[name=adminForm]").find("input[name=clipboard_task]").val(clipboardTask);
    }

    if (clipboardFiles != null) {
        jQuery("form[name=adminForm]").find("input[name=clipboard_files]").val(clipboardFiles);
    }

    if (clipboardSrc != null) {
        jQuery("form[name=adminForm]").find("input[name=clipboard_src]").val(clipboardSrc);
    }

    if (clipboardDest != null) {
        jQuery("form[name=adminForm]").find("input[name=clipboard_dest]").val(clipboardDest);
    }

    jQuery("form[name=adminForm]").submit();

}



function updateFileNames() {

    var result = "";
    if (filesSelected.length > 0) {

        var fileNames = [];
        for (var i = 0; i < filesSelected.length; i++) {
            fileNames[i] = "'" + filesSelected[i] + "'";
        }
        result = fileNames.join(" ");
    }
    jQuery("#file_names_span span").html(result);

}

// submit file

function submitFiles() {

    if (filesSelected.length == 0) {
        return;
    }

    var filesValid = [];
	
    for (var i = 0; i < filesSelected.length; i++) {
        var file_object = jQuery(".explorer_item[name='" + filesSelected[i] + "']");
		
        if (jQuery(file_object).attr("isDir") == "false") {
			//filesValid.push(dirUrl + "/" + filesSelected[i]);
			var fileData = [];
            fileData['name'] = filesSelected[i];
            fileData['filename'] = jQuery(file_object).attr("filename");
            fileData['url'] = dir + "/" + filesSelected[i];
            fileData['reliative_url'] = dirUrl + "/" + filesSelected[i];
            fileData['thumb_url'] = dir + "/thumb/" + filesSelected[i];
            fileData['thumb'] = jQuery(file_object).attr("filethumb");
            fileData['size'] = jQuery(file_object).attr("filesize");
            fileData['filetype'] = jQuery(file_object).attr("filetype");
            fileData['date_modified'] = jQuery(file_object).attr("date_modified");
            fileData['resolution'] = jQuery(file_object).attr("fileresolution");	
            fileData['fmTabIndexUnchanged'] = fmTabIndexUnchanged;
			
            filesValid.push(fileData);

        }		
    }
    window.parent[callback](filesValid);
    closeWindow();
}

function closeWindow() {
    window.parent.SqueezeBox.close();
}

function getScrollBarWidth() {

    var inner = document.createElement("p");
    inner.style.width = "100%";
    inner.style.height = "200px";

    var outer = document.createElement("div");

    outer.style.position = "absolute";
    outer.style.top = "0px";
    outer.style.left = "0px";
    outer.style.visibility = "hidden";
    outer.style.width = "200px";
    outer.style.height = "150px";
    outer.style.overflow = "hidden";
    outer.appendChild(inner);

    document.body.appendChild(outer);

    var w1 = inner.offsetWidth;
    outer.style.overflow = "scroll";
	
    var w2 = inner.offsetWidth;

    if (w1 == w2) w2 = outer.clientWidth;

    document.body.removeChild(outer);

    return (w1 - w2);

}

function getFileName(file) {
    var dotIndex = file.lastIndexOf('.');
    return file.substring(0, dotIndex < 0 ? file.length : dotIndex);
}

function getFileExtension(file) {
    return file.substring(file.lastIndexOf('.') + 1);
}



function selectAll() {

    var objName;
    jQuery(".explorer_item").addClass("explorer_item_select");
    filesSelected = [];

    jQuery(".explorer_item").each(function() {
        objName = jQuery(this).attr("name");
		
        if (filesSelected.indexOf(objName) == -1) {
            filesSelected.push(objName);
            keyFileSelected = this;
        }
    });
}


////////////////////////////////////////////////////////////////////////////////////////
// Listeners                                                                          //
////////////////////////////////////////////////////////////////////////////////////////

function onKeyDown(event) {

    if ((event.ctrlKey || event.metaKey) && event.which == 65) {
        selectAll();
        return false;
    }
}

//ctrls bar handlers

function onBtnUpClick(event, obj) {
    var destDir = dir.substring(0, dir.lastIndexOf(DS));
    submit("", null, null, null, destDir, null, null, null, null, null, null);
}

function onBtnMakeDirClick(event, obj) {
    var newDirName = prompt(messageEnterDirName);

    if ((newDirName) && (newDirName != "")) {
        submit("make_dir", null, null, null, null, null, newDirName, null, null, null, null);
    }
}



function onBtnRenameItemClick(event, obj) {

    if (filesSelected.length != 0) {
        var newName = prompt(messageEnterNewName, getFileName(filesSelected[0]));
        if ((newName != null) && (newName != "")) {
            submit("rename_item", null, null, null, null, newName, null, null, null, null, null);
        }
    }
}



function onBtnCopyClick(event, obj) {

    if (filesSelected.length != 0) {
        submit("", null, null, null, null, null, null, "copy", filesSelected.join("**#**"), dir, null);
    }
}

function onBtnCutClick(event, obj) {

    if (filesSelected.length != 0) {
        submit("", null, null, null, null, null, null, "cut", filesSelected.join("**#**"), dir, null);
    }
}

function onBtnPasteClick(event, obj) {

    if (getClipboardFiles() != "") {
        submit("paste_items", null, null, null, null, null, null, null, null, null, dir);
    }
}



function onBtnRemoveItemsClick(event, obj) {

    if ((filesSelected.length != 0) && (confirm(warningRemoveItems) == true)) {
        submit("remove_items", null, null, null, null, null, null, null, null, null, null);
    }
}

function onBtnShowUploaderClick(event, obj) {
    jQuery("#uploader").fadeIn();
}

function onBtnViewThumbsClick(event, obj) {
    submit("", null, null, "thumbs", null, null, null, null, null, null, null);
}



function onBtnViewListClick(event, obj) {
    submit("", null, null, "list", null, null, null, null, null, null, null);
}

function onBtnBackClick(event, obj) {
    hideUploader();
}

function onPathComponentClick(event, obj, path) {
    submit("", null, null, null, path, null, null, null, null, null, null);
}

function onNameHeaderClick(event, obj) {
    var newSortOrder = ((sortBy == "name") && (sortOrder == "asc")) ? "desc" : "asc";
    submit("", "name", newSortOrder, null, null, null, null, null, null, null, null);

}

function onSizeHeaderClick(event, obj) {
    var newSortOrder = ((sortBy == "size") && (sortOrder == "asc")) ? "desc" : "asc";
    submit("", "size", newSortOrder, null, null, null, null, null, null, null, null);

}

function onDateModifiedHeaderClick(event, obj) {
    var newSortOrder = ((sortBy == "date_modified") && (sortOrder == "asc")) ? "desc" : "asc";
    submit("", "date_modified", newSortOrder, null, null, null, null, null, null, null, null);

}

//file handlers

function onFileMOver(event, obj) {
	jQuery(obj).addClass("explorer_item_hover");
}

function onFileMOut(event, obj) {
    jQuery(obj).removeClass("explorer_item_hover");
}

function onFileClick(event, obj) {

    jQuery(".explorer_item").removeClass("explorer_item_select");
    var objName = jQuery(obj).attr("name");
	
    if (event.ctrlKey || event.metaKey) {

        if (filesSelected.indexOf(objName) == -1) {
		
            filesSelected.push(objName);
            keyFileSelected = obj;

        } else {

            filesSelected.splice(filesSelected.indexOf(objName), 1);
            jQuery(obj).removeClass("explorer_item_select");

        }

    } else if (event.shiftKey == true) {

        filesSelected = [];
        var explorerItems = jQuery(".explorer_item");
        var curFileIndex = explorerItems.index(jQuery(obj));
        var keyFileIndex = explorerItems.index(keyFileSelected);
        var startIndex = Math.min(keyFileIndex, curFileIndex);
        var endIndex = startIndex + Math.abs(curFileIndex - keyFileIndex);
		
        for (var i = startIndex; i < endIndex + 1; i++) {
            filesSelected.push(jQuery(explorerItems[i]).attr("name"));
        }

    } else {

        filesSelected = [jQuery(obj).attr("name")];
        keyFileSelected = obj;
    }

    for (var i = 0; i < filesSelected.length; i++) {
        jQuery(".explorer_item[name='" + filesSelected[i] + "']").addClass("explorer_item_select");
    }

    updateFileNames();
}

function onFileDblClick(event, obj) {

    if (jQuery(obj).attr("isDir") == "true") {
        submit("", null, null, null, dir + DS + jQuery(obj).attr("name"), null, null, null, null, null, null);

    } else {

        filesSelected = [];
        filesSelected.push(jQuery(obj).attr("name"));
        submitFiles();
    }
}

function onFileDragStart(event, obj) {

    var objName = jQuery(obj).attr("name");

    if (filesSelected.indexOf(objName) < 0) {

        jQuery(".explorer_item").removeClass("explorer_item_select");

        if (event.ctrlKey || event.metaKey) {

            if (filesSelected.indexOf(objName) == -1) {

                filesSelected.push(objName);
                keyFileSelected = obj;

            }

        } else if (event.shiftKey == true) {

            filesSelected = [];
            var explorerItems = jQuery(".explorer_item");
            var curFileIndex = explorerItems.index(jQuery(obj));
            var keyFileIndex = explorerItems.index(keyFileSelected);
            var startIndex = Math.min(keyFileIndex, curFileIndex);
			var endIndex = startIndex + Math.abs(curFileIndex - keyFileIndex);

            for (var i = startIndex; i < endIndex + 1; i++) {
                filesSelected.push(jQuery(explorerItems[i]).attr("name"));
            }

        } else {

            filesSelected = [jQuery(obj).attr("name")];
            keyFileSelected = obj;
        }

        for (var i = 0; i < filesSelected.length; i++) {
            jQuery(".explorer_item[name='" + filesSelected[i] + "']").addClass("explorer_item_select");
        }
        updateFileNames();
    }

    dragFiles = filesSelected;

}

function onFileDragOver(event, obj) {
    event.preventDefault();
}


function onFileDrop(event, obj) {

    var destDirName = jQuery(obj).attr("name");
    if ((dragFiles.length == 0) || (dragFiles.indexOf(destDirName) >= 0)) {
        return false;
    }

    var clipboardTask = (event.ctrlKey || event.metaKey) ? "copy" : "cut";
    var clipboardDest = dir + DS + destDirName;
    submit("paste_items", null, null, null, null, null, null, clipboardTask, dragFiles.join("**#**"), dir, clipboardDest);
    event.preventDefault();
}

function onBtnSelectAllClick() {
    selectAll();
}



function onBtnOpenClick(event, obj) {

    if (jQuery(".explorer_item[name='" + filesSelected[0] + "']").attr("isDir") == "true") {
        filesSelected.length = 1;
        submit("", null, null, null, dir + DS + filesSelected[0], null, null, null, null, null, null);

    } else {
        submitFiles();
    }
}

function onBtnCancelClick(event, obj) {
    closeWindow();
}

function onTabFilemanagerActivated(currentTabIndex) {
	jQuery('[name=adminForm] [name=fm_tab_index]').val(currentTabIndex);
	jQuery('[name=adminForm] [name=dir]').val('');
	
	if(wdShop_isJ3 == 0){
		jQuery("#tab_group_files dt").removeClass("open");
		jQuery("#tab_group_files dt."+currentTabIndex).addClass("open").removeClass("closed")
	}
	jQuery('[name=adminForm]').submit();
}