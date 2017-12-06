$.datepicker._defaults.isDateSelector = false; 
$.datepicker._defaults.onAfterUpdate = null;
$.datepicker._defaults.base_update = $.datepicker._updateDatepicker;
$.datepicker._defaults.base_generate = $.datepicker._generateHTML;

function DateRange(options) {
    if (!options.startField) throw "Missing Required Start Field!";
  	if (!options.endField) throw "Missing Required End Field!";
    
    var isDateSelector = true;
      
    var cur = -1,prv = -1, cnt = 0;   
    var df = options.dateFormat ? options.dateFormat:'mm/dd/yy';
    var RangeType = {ID:'rangetype',BOTH:0,START:1,END:2};
    var sData = {input:$(options.startField),div:$(document.createElement("DIV"))};
    var eData = {input:null,div:null};
     
    /*
     * Overloading JQuery DatePicker to add functionality - This should use WidgetFactory!
     */
     $.datepicker._updateDatepicker = function (inst) {
          var base = this._get(inst, 'base_update'); 
          base.call(this, inst);  
          if (isDateSelector) {
              var onAfterUpdate = this._get(inst, 'onAfterUpdate'); 
            if (onAfterUpdate) onAfterUpdate.apply((inst.input ? inst.input[0] : null), [(inst.input ?                  inst.input.val() : ''), inst]);
      }
    }; 

    $.datepicker._generateHTML = function (inst) {      
      var base = this._get(inst, 'base_generate');     
      var thishtml = base.call(this, inst);     
      var ds = this._get(inst, 'isDateSelector'); 
      if (isDateSelector) {
        thishtml = $('<div />').append(thishtml);                    
        thishtml = thishtml.children();
      }   
      return thishtml;
    };  
    
    function _hideSDataCalendar() {
    	sData.div.hide();
    }
    
    function _hideEDataCalendar() {  		
    	eData.div.hide();
    }
    
   
    function _handleOnSelect(dateText, inst, type) { 
    	var localeDateText = $.datepicker.formatDate(df, new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
        
    	// 0 = sData, 1 = eData
    	switch(cnt) {
    	case 0:                
    		sData.input.val(localeDateText);
    		eData.input.val('');
    		cnt=1;
	
    		break;
    	case 1: 
    		if (sData.input.val()) {
    			var s = $.datepicker.parseDate(df,sData.input.val()).getTime();
    			var e = $.datepicker.parseDate(df,localeDateText).getTime();
    			if (e >= s) {
    				eData.input.val(localeDateText);  
    				cnt=0;			
    			}
    		}                         
    	}               
    }
    
    
    function _handleBeforeShowDay(date, type) {
    	// Allow future dates?
    	var f = (options.allowFuture || date < new Date());
    	
        switch(type)
        {
          case RangeType.BOTH:             
            return [true, ((date.getTime() >= Math.min(prv, cur) && date.getTime() <= Math.max(prv, cur)) ?
            'ui-daterange-selected' : '')];   
            
          case RangeType.END:
            var s2 = null;
            if (sData.input && sData.input.val()) {
              try{
               s2 = $.datepicker.parseDate(df,sData.input.val()).getTime(); 
              }catch(e){}
            }
                        
            var e2 = null;
            if (eData.input && eData.input.val()) {
              try {
               e2 = $.datepicker.parseDate(df,eData.input.val()).getTime();
              }catch(e){}
            }
            
            var drs = 'ui-daterange-selected';
            var t = date.getTime();            
            if (s2 && !e2) {
              return [(t >= s2 || cnt === 0) && f, (t===s2) ? drs:'']; 
            }
            
            if (s2 && e2) {
              return [f, (t >= s2 && t <= e2) ? drs:'']; 
            }
            
            if (e2 && !s2) {
              return [t < e2 && f,(t < e2) ? drs:'']; 
            }
            
            return [f,''];               
        }   
    }
    
    function _attachCloseOnClickOutsideHandlers() {   	
    	$('html').click(function(e) {  		
    		var t = $(e.target);
    		if (sData.div.css('display') !== 'none') {
    			if (sData.input.is(t) || sData.div.has(t).length || /(ui-icon|ui-corner-all)/.test(e.target.className)) {
    				e.stopPropagation();
    			}else{
    				_hideSDataCalendar();  				
    			}	
    		}
    		if (eData && eData.div.css('display') !== 'none') {
    			if (eData.input.is(t) || eData.div.has(t).length || /(ui-icon|ui-corner-all)/.test(e.target.className)) {
    				e.stopPropagation();
    			}else{   				
    				_hideEDataCalendar();
    			}	   			
    		}
    	});	
    }
  
    function _alignContainer(data, alignment) {      
        var dir = {right:'left',left:'right'}; 
        var css = {
          position: 'absolute',
          top: data.input.position().top + data.input.outerHeight(true)
        };              
        css[alignment ? dir[alignment]:'right'] = '0em';
        data.div.css(css);
    }
    
    function _handleChangeMonthYear(year, month, inst) {
    	// What do we want to do here to sync?
    }
    
    function _focusStartDate(e) {
		cnt = 0;
		sData.div.datepicker('refresh');		
		_alignContainer(sData,options.opensTo);       
		sData.div.show();     
		_hideEDataCalendar();
    }
    
    function _focusEndDate(e) {
    	cnt = 1;
        _alignContainer(eData,options.opensTo);             
        eData.div.datepicker('refresh');
        eData.div.show();
        
        sData.div.datepicker('refresh');
        sData.div.hide();
    }
    
    // Build the start input element  
    sData.input.attr(RangeType.ID, options.endField ? RangeType.START : RangeType.BOTH);
    sData.div.attr('id',sData.input.attr('id')+'_cDiv');
    sData.div.addClass('ui-daterange-calendar');
    sData.div.hide();
    
    var pDiv = $(document.createElement("DIV"));        
    pDiv.addClass('ui-daterange-container');
    
    // Move the dom around         
    sData.input.before(pDiv);
    pDiv.append(sData.input.detach());
    pDiv.append(sData.div);
    
    sData.input.on('focus', _focusStartDate);   
    sData.input.keydown(function(e){if(e.keyCode==9){return false;}});
    sData.input.keyup(function(e){
    _handleKeyUp(e, options.endField ? RangeType.START : RangeType.BOTH);
   });
    
   _attachCloseOnClickOutsideHandlers(); 
    
   var sDataOptions = {	  
	    showButtonPanel: true,
	    changeMonth: true,
	    changeYear: true,
	    isDateSelector: true,    
	    beforeShow:function(){sData.input.datepicker('refresh');},
	    beforeShowDay: function(date){
	    	return _handleBeforeShowDay(date, options.endField ? RangeType.END : RangeType.BOTH);
	    },
	    onChangeMonthYear: _handleChangeMonthYear,
	    onSelect: function(dateText, inst) {
        return _handleOnSelect(dateText,inst,options.endField ? RangeType.END : RangeType.BOTH);
	    },
	    onAfterUpdate: function(){
	    	$('<button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all" data-handler="hide" data-event="click">Done</button>')    
	            .appendTo($('#'+sData.div.attr('id') + ' .ui-datepicker-buttonpane'))
	            .on('click', function () {
	            	sData.div.hide();	            	
            });
	    } 
	}; 
       
    sData.div.datepicker($.extend({}, options, sDataOptions));

    // Attach the end input element
    if (options.endField) {    
      eData.input = $(options.endField);     
      if (eData.input.length > 1 || !eData.input.is("input")) {
       throw "Illegal element provided for end range input!"; 
      }
      if (!eData.input.attr('id')) {eData.input.attr('id','dp_'+new Date().getTime());}
      eData.input.attr(RangeType.ID, RangeType.END);      
      eData.div = $(document.createElement("DIV"));
      eData.div.addClass('ui-daterange-calendar');
      eData.div.attr('id',eData.input.attr('id')+'_cDiv');
      eData.div.hide();
      
      pDiv = $(document.createElement("DIV"));    
      pDiv.addClass('ui-daterange-container');
     
      // Move the dom around         
      eData.input.before(pDiv);
      pDiv.append(eData.input.detach());
      pDiv.append(eData.div);  

      eData.input.on('focus', _focusEndDate);     
      // Add Keyup handler
      eData.input.keyup(function(e){
        _handleKeyUp(e, RangeType.END);
      });
      
      var eDataOptions = {
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        isDateSelector: true,    
        beforeShow:function(){sData.input.datepicker('refresh');},
	    beforeShowDay: function(date){
	      return _handleBeforeShowDay(date, RangeType.END);
	    },   
	    onSelect: function(dateText, inst) {
	      return _handleOnSelect(dateText,inst,RangeType.END);
	    },  
	    onAfterUpdate: function(){
	    	$('<button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all" data-handler="hide" data-event="click">Done</button>')    
	    	.appendTo($('#'+eData.div.attr('id') + ' .ui-datepicker-buttonpane'))
	    	.on('click', function () {
	    		eData.div.hide();
	    	});
	    } 
	  };
      
      eData.div.datepicker($.extend({}, options, eDataOptions));
    }
  
  return {
    // Returns an array of dates[start,end]
    getDates: function getDates() {
      var dates = [];
      var sDate = sData.input.val();
      if (sDate) {
        try {
          dates.push($.datepicker.parseDate(df,sDate));
        }catch(e){}
      }
      
      var eDate = (eData.input) ? eData.input.val():null;
      if (eDate) {
          try {
            dates.push($.datepicker.parseDate(df,eDate));
          }catch(e){}
      }
      return dates;
    },
    
    // Returns the end date as a js date
    getStartDate: function getStartDate() {
      try {
        return $.datepicker.parseDate(df,sData.input.val());
      }catch(e){}
    },
    
    // Returns the start date as a js date
    getEndDate: function getEndDate() {
      try {
        return $.datepicker.parseDate(df,eData.input.val());  
      }catch(e){}
    }
  };
}


var cfg = {startField: '#fromDate', endField: '#toDate',opensTo: 'Left', numberOfMonths: 3, defaultDate: -50};
var dr = new DateRange(cfg);
