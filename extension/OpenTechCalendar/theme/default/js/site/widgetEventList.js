/**
 * OpenTechCalendar Event List Widget
 * @author James Baster <james@jarofgreen.co.uk>
 * @license All rights reserved. 
 */

var OpenTechCalendarEventListWidgetCssAdded = false;
var OpenTechCalendarEventListCallBackFunctionCount = 0;

function placeOpenTechCalendarEventListWidget(divid, options) {
       var usingOptions = {
               eventCount: 5,
               title: undefined,
               maxStringLength: 300,
               groupID: undefined,
               locationID: undefined,
               curatedListID: undefined,
               openInNewWindow: true
       }
       for (var prop in options) {
               if (options.hasOwnProperty(prop)) {
                       usingOptions[prop] = options[prop];
               }
       }
       var div = document.getElementById(divid);
       if (!div) return;
       var moreURL;
       if (usingOptions.groupID) {
               moreURL = "http://opentechcalendar.co.uk/group/"+usingOptions.groupID;
       } else if (usingOptions.locationID) {
               moreURL = "http://opentechcalendar.co.uk/location/"+usingOptions.locationID;
       } else if (usingOptions.curatedListID) {
               moreURL = "http://opentechcalendar.co.uk/list/"+usingOptions.curatedListID;
       } else {
               moreURL = "http://opentechcalendar.co.uk/event/";
       }

       var target = usingOptions['openInNewWindow'] ? ' target="_BLANK"' : '';

       div.innerHTML = '<div class="openTechCalendarEventListData">'+
                       '<div class="openTechCalendarEventListHeader"><a href="'+moreURL+'" '+target+' id="'+divid+'Title">'+escapeHtmlOpenTechCalendarEventList(usingOptions.title?usingOptions.title:'Events')+'</a></div>'+
                       '<div class="openTechCalendarEventListEvents" id="'+divid+'Data">Loading</div>'+
                       '<div class="openTechCalendarEventListFooter">'+
                               '<div class="openTechCalendarEventListFooterMore"><a href="'+moreURL+'" '+target+'>See more ...</a></div>'+
                               '<div class="openTechCalendarEventListFooterCredit">Data from <a href="http://opentechcalendar.co.uk" '+target+'>Open Tech Calendar</a></div>'+
                               '<div class="openTechCalendarEventListFooterSponsor" id="'+divid+'Sponsor">&nbsp;</div>'+
                       '</div>'+
               '</div>';
       var dataDiv = document.getElementById(divid+"Data");
       var headTag = document.getElementsByTagName('head').item(0);
               
       if (!OpenTechCalendarEventListWidgetCssAdded) { 
               var link = document.createElement("link");
               link.type = "text/css"; 
               link.href = "http://opentechcalendar.co.uk/css/widgetEventList.css?v=1"; 
               link.rel = "stylesheet"; 
               headTag.appendChild(link);
               OpenTechCalendarEventListWidgetCssAdded = true;
       }

       OpenTechCalendarEventListCallBackFunctionCount++;
       window["OpenTechCallBackFunction"+OpenTechCalendarEventListCallBackFunctionCount] = function(data) {                    
               var html = '';
               var limit = Math.min(data.data.length, usingOptions.eventCount);
               if (limit <= 0) {
                       html = '<div class="openTechCalendarEventListEventNone">No events</div>';
               } else {
                       for (var i=0;i<limit;i++) {
                               html += htmlFromEventOpenTechCalendarEventList(data.data[i], usingOptions.maxStringLength, target);
                       }
               }

               dataDiv.innerHTML=html;
               
               if (data.sponsorsHTML) {
                       var sponsorDiv = document.getElementById(divid+"Sponsor");
                       sponsorDiv.innerHTML = 'Sponsored by '+data.sponsorsHTML;
               }
               
               if (!usingOptions.title) {
                       var titleDiv = document.getElementById(divid+"Title");
                       titleDiv.innerHTML = data.title;
               }
       }
       var url;
       if (usingOptions.groupID) {
               url = "http://opentechcalendar.co.uk/api1/group/"+usingOptions.groupID+"/jsonp";
       } else if (usingOptions.locationID) {
               url = "http://opentechcalendar.co.uk/api1/legacylocation/"+usingOptions.locationID+"/jsonp";
       } else if (usingOptions.curatedListID) {
               url = "http://opentechcalendar.co.uk/api1/list/"+usingOptions.curatedListID+"/jsonp";
       } else {
               url = "http://opentechcalendar.co.uk/api1/event/jsonp";
       }

       var script = document.createElement("script");
       script.type = "text/javascript"; 
       script.src = url+"?callback=OpenTechCallBackFunction"+OpenTechCalendarEventListCallBackFunctionCount;
       headTag.appendChild(script);
       
}

function htmlFromEventOpenTechCalendarEventList(event, maxLength, target) {
       var html = '<div class="openTechCalendarEventListEvent">'
       html += '<div class="openTechCalendarEventListDate">'+event.start.displaylocal+'</div>';
       html += '<div class="openTechCalendarEventListSummary"><a href="'+event.siteurl+'" '+target+'>'+escapeHtmlOpenTechCalendarEventList(event.summaryDisplay)+'</a></div>';
       html += '<div class="openTechCalendarEventListDescription">'+escapeHtmlNewLineOpenTechCalendarEventList(event.description, maxLength)+'</div>';
       html += '<a class="openTechCalendarEventListMoreLink" href="'+event.siteurl+'" '+target+'>More Info</a>';
       html += '<div class="openTechCalendarEventListClear"></div>';   
       return html+'</div>';
}

function escapeHtmlOpenTechCalendarEventList(str) {
       var div = document.createElement('div');
       div.appendChild(document.createTextNode(str));
       return div.innerHTML;
};

function escapeHtmlNewLineOpenTechCalendarEventList(str, maxLength) {
       var div = document.createElement('div');
       div.appendChild(document.createTextNode(str));
       var out =  div.innerHTML;
       if (out.length > maxLength) {
               out = out.substr(0,maxLength)+" ...";
       }
       return out.replace(/\n/g,'<br>');
};

