(function () {
    if (typeof jQuery === 'undefined') {
        throw new Error('Slider\'s JavaScript requires jQuery')
    }

    if (navigator.appName === 'Microsoft Internet Explorer') { //IE
        if (navigator.userAgent.match(/Trident/i) && navigator.userAgent.match(/MSIE 8.0/i)) { //TridentIE8.0
            //do something
        }
    }
    var _startTimeStamp; //IE8mousmove，;
    var _stopTimeStamp;
    var _gContextArray = new Array(); //
    var _gLanguage = 1; //，
    var mTop = 20;
    var counter = 0;
    var mycount = 0;

    function TimeSlider(initObj) {
        this.left_array = new Array(); //
        this.right_array = new Array(); //
        this.leftTime_array = new Array(); //
        this.rightTime_array = new Array(); //
        this.events_array = new Array(); //
        this.leftTime = 0; //
        this.rightTime = 0; //
        this.offsetX_left = 0; //
        this.timeSliderWidth = 0; //
        this.slderLeftOffset = 0; //
        this.oneTimeBlockWidth = 0; //
        this.oneHourWidth = 0; //
        this.oneMinWidth = 0; //
        this.timeBlockNum = 0; //-1
        this.hasMove = false; //
        this.whichOne = 0; //
        this.timeSliderNum = 0; //
        this.timeBlockId_present = null; //
        this.mountId = null; //
        this.currentEvent = null; //

        //_gLanguage = initObj.language == "en" ? 0 : 1;
        this.init(initObj); //
    }


    TimeSlider.prototype = {
        sliderTotal: 0, //
        init: function (obj) {
            _gContextArray.push(this);
            TimeSlider.prototype.sliderTotal++;
            this.timeSliderNum = TimeSlider.prototype.sliderTotal;
            counter = 0;
            this.createLayout(obj);
        },

        createLayout: function (obj) {

            var self = this; //
            self.mountId = obj.id;
            self.events_array = obj.defaultEvents || [];
            var defaultOneTimeBlockTime = obj.defaultOneTimeBlockTime || 30;

            /*********************start******************/
            var backgroundDiv = document.createElement("div");
            $(backgroundDiv).addClass("trCanvas").appendTo("#" + self.mountId);

            $(backgroundDiv).mousedown(function (e) {
                self.manualCreation(e);
            })

            var oneHourWidth = Math.floor($(backgroundDiv).width() / 23.5); //
            self.oneHourWidth = oneHourWidth;
            var oneMinWidth = parseFloat((oneHourWidth / 60).toFixed(4)); //
            self.oneMinWidth = oneMinWidth;
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
            var h = dt.getHours() * 60;
            var m = dt.getMinutes();
            $(".time").css({
                'left': self.oneMinWidth * (h + m) + 15,
                'z-index': 99999
            })
            //var oneTimeBlockWidth = parseFloat((oneMinWidth * defaultOneTimeBlockTime).toFixed(4)); //
            //self.oneTimeBlockWidth = oneTimeBlockWidth;

            self.timeSliderWidth = $(backgroundDiv).width(); //

            //

            for (var i = 0; i < 24; i++) {
                var summ = oneHourWidth * i;
                if (summ != 0) {
                    var coordinateDiv = document.createElement("div");
                    $(coordinateDiv).addClass("coordinate").css({
                        "left": summ + "px"
                    }).appendTo("#" + self.mountId);


                    var labelDiv = document.createElement("div");
                    if (i < 10) {
                        $(labelDiv).addClass("coordinateLabDiv").text(i).css({
                            "left": summ + "px"
                        }).appendTo("#" + self.mountId);
                    } else {
                        $(labelDiv).addClass("coordinateLabDiv").text(i).css({
                            "left": summ + "px",
                            "margin-left": "-6px"
                        }).appendTo("#" + self.mountId);

                    }
                    /*if (i == 24) {
                        mycount++;
                        $(".timeSlider").append('<div id="myas' + mycount + '" class="underLine"></div>');
                    }*/
                }
                //i==24?$(".timeSlider").append('<div class="underLine"></div>'):null	;


            }


            if (counter == 0) {
                for (var i = 0; i < 24; i++) {
                    var summ = oneHourWidth * i;
                    if (summ != 0) {
                        var coordinateDiv = document.createElement("div");
                        $(".underLine").append(`<div class='coordinateUnder' style='left : ${summ}px'></div>`).css({
                            'margin-top': 30 + "px"
                        })

// 				if(i==24)mTop+=20;
// 				i==24?counter=1:null;
                    }
                    counter++;
                }
            }
            //
            getInstance(createCoverDiv);
            getInstance(createPopUpBox);
            //
            //creatEditDiv(self);
            /*********************end******************/

            //self.slderLeftOffset = $("#" + self.mountId).offset().left; //


            /******************start*****************/
            if (Object.prototype.toString.call(obj.defaultTime) == "[object Array]") {
                self.timeInit(obj.defaultTime, obj.tour_id, obj.driver_id);
            } else if (obj.defaultTime) {
                throw new Error('err');
            }

        },
        manualCreation: function (e) {
            var self = this;
            var offsetX_left = parseFloat((e.pageX - self.slderLeftOffset).toFixed(4));
            var offsetX_right = parseFloat((offsetX_left + self.oneTimeBlockWidth).toFixed(4));
            var tmpStartHour = Math.floor(offsetX_left / self.oneHourWidth);
            var tmpStartMin = Math.round(offsetX_left % self.oneHourWidth / self.oneMinWidth);
            var tmpStopHour = Math.floor(offsetX_right / self.oneHourWidth);
            var tmpStopMin = Math.round(offsetX_right % self.oneHourWidth / self.oneMinWidth);
            if (tmpStartMin < 10) {
                tmpStartMin = "0" + tmpStartMin;
            } else if (tmpStartMin == 60) {
                tmpStartMin = "00";
                tmpStartHour += 1;
            }
            if (tmpStopMin < 10) {
                tmpStopMin = "0" + tmpStopMin;
            } else if (tmpStopMin == 60) {
                tmpStopMin = "00";
                tmpStartHour += 1;
            }
            var hour1 = tmpStartHour.toString().length < 2 ? "0" + tmpStartHour : tmpStartHour;
            var time1 = hour1 + ":" + tmpStartMin;

            var hour2 = tmpStopHour.toString().length < 2 ? "0" + tmpStopHour : tmpStopHour;
            var time2 = hour2 + ":" + tmpStopMin;

            var timeArray = new Array(); //
            var offsetXArray = new Array(); //

            timeArray.push(time1);
            timeArray.push(time2);
            offsetXArray = self.getSliderOffsetX(timeArray);
            self.createTimeBlock({
                backgroundDiv: self.mountId,
                offsetX_left: offsetXArray[0],
                offsetX_right: offsetXArray[1],
            })

            //
            whichOne = _.sortedIndex(self.left_array, self.offsetX_left);
            self.events_array.splice(whichOne, 0, 0);
        },
        //
        timeInit: function (data, tour_id, driver_id) {
            $(".editContent").hide();
            $(".editUnit>input[type=checkbox],.editCheckAll").prop("checked", false);
            _.map(data, function (item, index) {
                var startTime = item.split("-")[0];
                var stopTime = item.split("-")[1];
                var timeArray = new Array(); //
                var offsetXArray = new Array(); //
                var event = this.events_array[index];

                timeArray.push(startTime);
                timeArray.push(stopTime);
                offsetXArray = this.getSliderOffsetX(timeArray);
                this.createTimeBlock({
                    backgroundDiv: this.mountId,
                    offsetX_left: offsetXArray[0],
                    offsetX_right: offsetXArray[1],
                    event: event,
                    tour_id : tour_id[index],
                    driver_id : driver_id[index],

                })
            }
                .bind(this))
        },
        createTimeBlock: function (obj) {
            var self = this;
            var backgroundDiv = obj.backgroundDiv;
            var tour_id = obj.tour_id;
            var driver_id = obj.driver_id;
            var offsetX_left = parseFloat(obj.offsetX_left.toFixed(4));
            var offsetX_right = obj.offsetX_right || parseFloat((offsetX_left + self.oneTimeBlockWidth).toFixed(4));
            var event = obj.event || 0;
            /**************start***************/
            if (offsetX_right <= offsetX_left) {
                return;
            }

            if (typeof(backgroundDiv) == "string") {
                $backgroundDiv = $("#" + backgroundDiv + " > .trCanvas");
            } else {
                $backgroundDiv = $(backgroundDiv);
            }

            if ((offsetX_left) > $backgroundDiv.width() - self.oneTimeBlockWidth) {
                return;
            }

            var leftArrayLength = self.left_array.length;
            if (leftArrayLength >= 1) {
                for (var j = 0; j < leftArrayLength; j++) {
                    if (offsetX_right > self.left_array[j] && offsetX_left < self.right_array[j]) {
                        return;
                    }
                }
            }
            /**************end****************/

            /**************start****************/
            self.left_array.push(offsetX_left);
            self.right_array.push(offsetX_right);
            self.offsetX_left = offsetX_left;
            //
            self.left_array.sort(function (a, b) {
                return a - b;
            });
            self.right_array.sort(function (a, b) {
                return a - b;
            });

            var sliderNum = self.timeSliderNum; //

            //
            var timeBlockDataObj = {
                sliderNum: sliderNum,
                tour_id: tour_id,
                driver_id: driver_id,
                timeBlockNum: self.timeBlockNum,
                offsetX_left: offsetX_left,
                timeBlockWidth: parseFloat((offsetX_right - offsetX_left).toFixed(4)),
                defalutColor: defalutColor[event]
            }
            var timeBlockSting = [
                '<div data-tour_id="<%=tour_id%>" data-driver_id="<%=driver_id%>" class="timeSliderDiv"',
                'draggable="true"',
                'ondragstart="drag(event)"',
                'id="timeS<%=sliderNum%>_<%=timeBlockNum%>"',
                'style=left:<%=offsetX_left%>px;',
                'width:<%=timeBlockWidth%>px;',
                '><div  class="main-block">\n' +
                '<div style="display: none;" class="l-txt"><i class="fas fa-map-marker-alt"></i><span class="main-txt2">Уфа</span></div>\n' +
                '<button  type="button" class="btn btn-success main-button">Аренда</button>\n' +
                '<div style="display: none; class="r-txt"><i class="fas fa-map-marker-alt"></i><span class="main-txt">Казань</span></div>\n' +
                '<select style="    height: 30px;" class="form-control main-select js_select_driver" >\n' +
                '</select>\n' +
                '</div></div>'
            ].join("");
            timeBlockSting = _.template(timeBlockSting);
            var timeBlockDom = timeBlockSting(timeBlockDataObj);
            $backgroundDiv.append(timeBlockDom);

            //
            /*$("#timeS" + sliderNum + "_" + self.timeBlockNum).mousedown(function (e) {
                self.timeBlockMouseDown(e, this);
                if (document.all) { //IE8
                    e.originalEvent.cancelBubble = true;
                } else {
                    e.stopPropagation();
                }
            })*/

            /**************start****************/
            var bar_string = [
                '<div class="<%=barClass%>"',
                'id="<%=barId%>"',
                '></div>'
            ].join("");
            bar_string = _.template(bar_string);

            var bar_left_dataObj = {
                barClass: 'leftBar',
                barId: 'leftBar' + sliderNum + '_' + self.timeBlockNum
            }

            var bar_right_dataObj = {
                barClass: 'rightBar',
                barId: 'rightBar' + sliderNum + '_' + self.timeBlockNum
            }

            var bar_left_dom = bar_string(bar_left_dataObj);
            var bar_right_dom = bar_string(bar_right_dataObj);

            //$("#timeS" + sliderNum + "_" + self.timeBlockNum).append(bar_left_dom);
            //$("#timeS" + sliderNum + "_" + self.timeBlockNum).append(bar_right_dom);

            /*$("#leftBar" + sliderNum + "_" + self.timeBlockNum).mouseover(function (e) {
                $(this).css("cursor", "e-resize");
                if (document.all) { //IE8
                    e.originalEvent.cancelBubble = true;
                } else {
                    e.stopPropagation();
                }
            }).mousedown(function (e) {
                self.leftBarDown(e, this);
                if (document.all) { //IE8
                    e.originalEvent.cancelBubble = true;
                } else {
                    e.stopPropagation();
                }
            })

            $("#rightBar" + sliderNum + "_" + self.timeBlockNum).mouseover(function (e) {
                $(this).css("cursor", "e-resize");
                if (document.all) { //IE8
                    e.originalEvent.cancelBubble = true;
                } else {
                    e.stopPropagation();
                }
            }).mousedown(function (e) {
                self.rightBarDown(e, this);
                if (document.all) { //IE8
                    e.originalEvent.cancelBubble = true;
                } else {
                    e.stopPropagation();
                }
            })*/

            /**************end****************/

            /*************start***************/
            var time_show_string = [
                '<div class="<%=timeShowClass%>"',
                'id="<%=timeShowId%>"',
                '></div>'
            ].join("");
            time_show_string = _.template(time_show_string);

            var time_show_left_dataObj = {
                timeShowClass: 'leftShow',
                timeShowId: 'leftShow' + sliderNum + '_' + self.timeBlockNum
            }

            var time_show_right_dataObj = {
                timeShowClass: 'rightShow',
                timeShowId: 'rightShow' + sliderNum + '_' + self.timeBlockNum
            }
            var time_show_left_dom = time_show_string(time_show_left_dataObj);
            var time_show_right_dom = time_show_string(time_show_right_dataObj);

            $("#timeS" + sliderNum + "_" + self.timeBlockNum).append(time_show_left_dom);
            $("#timeS" + sliderNum + "_" + self.timeBlockNum).append(time_show_right_dom);

            self.setSliderTime(offsetX_left, 'leftShow' + sliderNum + '_' + self.timeBlockNum);
            self.setSliderTime(offsetX_right, 'rightShow' + sliderNum + '_' + self.timeBlockNum);
            self.getSliderTime();
            /**************end****************/

            //
            $("#timeS" + sliderNum + "_" + self.timeBlockNum).hover(function () {
                $(this).addClass("hover");
                $(this).css("z-index", 5);
                $(".hover .leftShow").show();
                $(".hover .rightShow").show();
                $(".hover .leftBar").show();
                $(".hover .rightBar").show();
            }, function () {
                $(this).css("z-index", 4);
                $(".hover .leftShow").hide();
                $(".hover .rightShow").hide();
                $(".hover .leftBar").hide();
                $(".hover .rightBar").hide();
                $(this).removeClass("hover");
            })

            self.timeBlockNum++;
            /**************end****************/
        },

        set: function (obj) {
            var setTimeArray = obj.setTimeArray;
            if (Object.prototype.toString.call(setTimeArray) == "[object Array]") {
                removeAll(this);
                if (0 === setTimeArray.length) {
                    return;
                }
                this.events_array = obj.setEventsArray || [];
                this.timeInit(setTimeArray);
            } else if (setTimeArray) {
                throw new Error('err');
            }
        },
        /*,*/
        get: function () {
            var resObj = {};
            var self = this;
            resObj.times = new Array();
            resObj.events = self.events_array;
            _.forEach(self.leftTime_array, function (item, index) {
                resObj.times.push(item + "-" + self.rightTime_array[index]);
            })
            return resObj;
        },
        /*，*/
        timeBlockMouseDown: function (e, thisTimeBlock) {
            _startTimeStamp = new Date().getTime();
            _startTimeStamp = new Date().getTime();
            $(thisTimeBlock).css("z-index", "5");
            $(thisTimeBlock).css("cursor", "move");
            var self = this;
            var rightShowId = $(thisTimeBlock).children(".rightShow").attr("id");
            var leftShowId = $(thisTimeBlock).children(".leftShow").attr("id");
            var arrayLength = self.right_array.length;
            var whichOne;
            /*，*/
            whichOne = _.sortedIndex(self.leftTime_array, $("#" + leftShowId).text());
            var parentOriginalLeft = self.left_array[whichOne]; //
            var mouseRelativeOffsetX = parseFloat((e.pageX - parentOriginalLeft - self.slderLeftOffset).toFixed(4)); //
            self.whichOne = whichOne;
            var timeBlockId = $(thisTimeBlock).attr("id");
            var timeSliderWidth = self.timeSliderWidth; //
            self.timeBlockId_present = timeBlockId;
            //
            var leftBorder = 0; //
            var rightBorder = timeSliderWidth; //
            var leftOffset = 0; //
            var rightOffset = 0;
            if (arrayLength > 1) {
                if (self.whichOne == 0) {
                    rightBorder = self.left_array[self.whichOne + 1];
                } else if (self.whichOne == arrayLength - 1) {
                    leftBorder = self.right_array[self.whichOne - 1];
                } else {
                    leftBorder = self.right_array[self.whichOne - 1];
                    rightBorder = self.left_array[self.whichOne + 1];
                }
            }

            var timeBlockWidth = parseFloat((self.right_array[self.whichOne] - self.left_array[self.whichOne]).toFixed(4)); //
            $(document).mousemove(function (ev) {
                _stopTimeStamp = new Date().getTime();
                if (_stopTimeStamp - _startTimeStamp > 80) {
                    self.hasMove = true;
                    leftOffset = parseFloat((ev.pageX - mouseRelativeOffsetX - self.slderLeftOffset).toFixed(4));
                    if (leftOffset <= leftBorder) {
                        leftOffset = leftBorder;
                    } else if (leftOffset >= parseFloat((rightBorder - timeBlockWidth).toFixed(4))) {
                        leftOffset = parseFloat((rightBorder - timeBlockWidth).toFixed(4));
                    }
                    rightOffset = parseFloat((leftOffset + timeBlockWidth).toFixed(4));

                    if (leftOffset >= leftBorder && rightOffset <= rightBorder) {
                        $("#" + timeBlockId).css({
                            left: leftOffset + "px"
                        });
                        self.setSliderTime(leftOffset, leftShowId);
                        self.setSliderTime(rightOffset, rightShowId);
                    }
                }
            })

            $(document).on("mouseup mouseleave", function (e) {
                if (!self.hasMove) {
                    /*var rightShowId = $("#" + timeBlockId).children(".rightShow").attr("id");
                    var leftShowId = $("#" + timeBlockId).children(".leftShow").attr("id");
                    $("#startH").val(parseInt($("#" + leftShowId).text().split(":")[0], 10));
                    $("#startM").val(parseInt($("#" + leftShowId).text().split(":")[1], 10));
                    $("#stopH").val(parseInt($("#" + rightShowId).text().split(":")[0], 10));
                    $("#stopM").val(parseInt($("#" + rightShowId).text().split(":")[1], 10));
                    var color = $("#" + timeBlockId).css("backgroundColor");
                    if (_.indexOf(color, "#") == -1) {
                        var colorHex = rgbToHex($("#" + timeBlockId).css("backgroundColor"));
                    } else {
                        var colorHex = color.replace('#', "");
                    }
                    var eventIndex = _.indexOf(defalutColor, colorHex) + 1;
                    $("#eventSelect").val(eventIndex);
                    $("#fixedDiv").fadeIn(100);
                    $("#modalDiv").fadeIn(150).attr("data-number", self.timeSliderNum);*/
                } else {
                    self.hasMove = false;
                    self.getSliderTime("move");
                    var tmpTimeArray = new Array();
                    tmpTimeArray.push(self.leftTime_array[self.whichOne], self.rightTime_array[self.whichOne]);
                    tmpTimeArray = self.getSliderOffsetX(tmpTimeArray);
                    self.left_array[self.whichOne] = parseFloat(tmpTimeArray[0].toFixed(4));
                    self.right_array[self.whichOne] = parseFloat(tmpTimeArray[1].toFixed(4));
                    $("#" + timeBlockId).css({
                        left: parseFloat(tmpTimeArray[0].toFixed(4)) + "px"
                    });
                }
                $("#" + timeBlockId).css("cursor", "auto");
                $(document).off("mousemove mouseup mouseleave");
            })
            if (document.all) { //兼容IE8
                e.originalEvent.cancelBubble = true;
            } else {
                e.stopPropagation();
            }
        },

        leftBarDown: function (e, thisBar) {
            _startTimeStamp = new Date().getTime();
            $(thisBar).css("cursor", "w-resize");
            var self = this;
            var timeBlockId = $(thisBar).parent().attr("id");
            var leftShowId = $("#" + timeBlockId).children(".leftShow").attr("id");
            var whichOne;
            var len = self.left_array.length;
            var offsetX_left_present;
            whichOne = _.sortedIndex(self.leftTime_array, $("#" + leftShowId).text());
            var parentOriginalLeft = self.left_array[whichOne]
            var mouseRelativeOffsetX = parseInt(parentOriginalLeft - (e.pageX - self.slderLeftOffset), 10);
            //var mouseRelativeOffsetX = 0;
            self.whichOne = whichOne;
            var leftBorder = 0; //；

            if (len > 1) {
                if (whichOne != 0) {
                    leftBorder = self.right_array[whichOne - 1];
                }
            }
            /**/
            $(document).mousemove(function (ev) {
                _stopTimeStamp = new Date().getTime();
                if (_stopTimeStamp - _startTimeStamp > 100) { //
                    offsetX_left_present = parseFloat((ev.pageX - self.slderLeftOffset + mouseRelativeOffsetX).toFixed(4)); //
                    if (offsetX_left_present <= leftBorder) {
                        offsetX_left_present = leftBorder;
                    }
                    var timeBlockWidth_present = parseFloat((self.right_array[whichOne] - offsetX_left_present).toFixed(4)); //
                    if (timeBlockWidth_present >= self.oneTimeBlockWidth) { //
                        if (offsetX_left_present >= leftBorder) {
                            $("#" + timeBlockId).css({
                                width: timeBlockWidth_present + "px",
                                left: offsetX_left_present + "px"
                            });
                            self.setSliderTime(offsetX_left_present, leftShowId);
                        }
                    }
                }
            })

            $(document).on("mouseup mouseleave", function (e) {
                self.barUp(thisBar, "left", timeBlockId);
            })
            if (document.all) { //IE8
                e.originalEvent.cancelBubble = true;
            } else {
                e.stopPropagation();
            }
        },
        rightBarDown: function (e, thisBar) {
            var self = this;
            _startTimeStamp = new Date().getTime();
            var timeBlockId = $(thisBar).parent().attr("id"); //
            var rightShowId = $("#" + timeBlockId).children(".rightShow").attr("id");
            var whichOne;
            var timeSliderWidth = self.timeSliderWidth; //
            var len = self.left_array.length;
            var offsetX_right_present;

            whichOne = _.sortedIndex(self.rightTime_array, $("#" + rightShowId).text()); //
            self.whichOne = whichOne;
            var parentOriginalLeft = self.left_array[whichOne]; //
            var mouseRelativeOffsetX = parseFloat((e.pageX - self.slderLeftOffset - self.right_array[whichOne]).toFixed(4)); //
            //
            var rightBorder = timeSliderWidth; //

            if (len > 1) {
                if (whichOne != len - 1) {
                    rightBorder = self.left_array[whichOne + 1];
                }
            }
            $(document).mousemove(function (ev) {
                _stopTimeStamp = new Date().getTime();
                if (_stopTimeStamp - _startTimeStamp > 50) { //
                    offsetX_right_present = parseFloat((ev.pageX - self.slderLeftOffset - mouseRelativeOffsetX).toFixed(4));
                    if (offsetX_right_present >= timeSliderWidth) {
                        offsetX_right_present = timeSliderWidth;
                    }

                    var timeSliderWidth_present = parseFloat((offsetX_right_present - parentOriginalLeft).toFixed(4)); //

                    if (timeSliderWidth_present >= self.oneTimeBlockWidth) {
                        if (offsetX_right_present >= rightBorder) {
                            offsetX_right_present = rightBorder;
                        }
                        var timeSliderWidth_present = parseFloat((offsetX_right_present - parentOriginalLeft).toFixed(4));
                        $("#" + timeBlockId).css({
                            width: timeSliderWidth_present + "px"
                        });
                        self.setSliderTime(offsetX_right_present, rightShowId);
                    }

                }
            })
            $(document).on("mouseup mouseleave", function (e) {
                self.barUp(thisBar, "right", timeBlockId);
            })
            if (document.all) { //兼容IE8
                e.originalEvent.cancelBubble = true;
            } else {
                e.stopPropagation();
            }
        },
        barUp: function (thisBar, direction, timeBlockId) {
            var self = this;
            $(thisBar).css("cursor", "default");
            $(document).off("mousemove mouseup mouseleave");
            self.getSliderTime("move", direction); //
            var tmpTimeArray = new Array();
            tmpTimeArray.push(self.leftTime_array[self.whichOne], self.rightTime_array[self.whichOne]);
            tmpTimeArray = self.getSliderOffsetX(tmpTimeArray);
            self.left_array[self.whichOne] = parseFloat(tmpTimeArray[0].toFixed(4));
            self.right_array[self.whichOne] = parseFloat(tmpTimeArray[1].toFixed(4));
            $("#" + timeBlockId).css({
                width: parseFloat((tmpTimeArray[1] - tmpTimeArray[0]).toFixed(4)) + "px",
                left: parseFloat(tmpTimeArray[0].toFixed(4)) + "px"
            });
        },
        setSliderTime: function (offsetX, id) {
            var direction = id.substring(0, 1);
            var self = this;
            var tmpHour = Math.floor(offsetX / self.oneHourWidth);
            var min = Math.round(offsetX % self.oneHourWidth / self.oneMinWidth);
            if (min < 10) {
                min = "0" + min;
            } else if (min == 60) {
                min = "00";
                tmpHour += 1;
            }
            var hour = tmpHour.toString().length < 2 ? "0" + tmpHour : tmpHour;

            $("#" + id).text(hour + ":" + min);
            if (direction == "l") {
                this.leftTime = hour + ":" + min;
            } else {
                this.rightTime = hour + ":" + min;
            }
        },
        getSliderTime: function (action, direction) {
            if (action == "move") {
                if ("right" === direction) {
                    this.rightTime_array[this.whichOne] = this.rightTime;
                } else if ("left" === direction) {
                    this.leftTime_array[this.whichOne] = this.leftTime;
                } else {
                    this.rightTime_array[this.whichOne] = this.rightTime;
                    this.leftTime_array[this.whichOne] = this.leftTime;
                }
            } else {
                this.leftTime_array.push(this.leftTime);
                this.rightTime_array.push(this.rightTime);
            }
            this.leftTime_array.sort(function (a, b) {
                var A = parseInt(a.split(":")[0], 10) * 60 + parseInt(a.split(":")[1], 10);
                var B = parseInt(b.split(":")[0], 10) * 60 + parseInt(b.split(":")[1], 10);
                return A - B;
            });
            this.rightTime_array.sort(function (a, b) {
                var A = parseInt(a.split(":")[0], 10) * 60 + parseInt(a.split(":")[1], 10);
                var B = parseInt(b.split(":")[0], 10) * 60 + parseInt(b.split(":")[1], 10);
                return A - B;
            });

        },
        getSliderOffsetX: function (time) {
            var offsetX_Array = new Array;
            var self = this;
            var startH_OffsetX = parseInt(time[0].split(":")[0], 10) * self.oneHourWidth;
            var startM_OffsetX = parseInt(time[0].split(":")[1], 10) * self.oneMinWidth;
            var a = parseInt(time[0].split(":")[0], 10);
            var b = parseInt(time[0].split(":")[1], 10);
            startM_OffsetX = parseFloat(startM_OffsetX.toFixed(4));
            var stopH_OffsetX = parseInt(time[1].split(":")[0], 10) * self.oneHourWidth;
            var stopM_OffsetX = parseInt(time[1].split(":")[1], 10) * self.oneMinWidth;
            stopM_OffsetX = parseFloat(stopM_OffsetX.toFixed(4));
            var startTime_OffsetX = startH_OffsetX + startM_OffsetX;
            var stopTime_OffsetX = stopH_OffsetX + stopM_OffsetX;
            offsetX_Array[0] = startTime_OffsetX;
            offsetX_Array[1] = stopTime_OffsetX;
            return offsetX_Array
        },

        getStyle: function (element, attr) {
            if (element.currentStyle) {
                return element.currentStyle[attr];
            } else {
                return getComputedStyle(element, false)[attr];
            }
            return style;
        },
        getMousePos: function (event) {
            var e = event || window.event;
            var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
            var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
            var x = e.pageX || e.clientX + scrollX;
            var y = e.pageY || e.clientY + scrollY;
            return {
                'x': x,
                'y': y
            };
        },
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////
    /****************start******************/

    if (!Function.prototype.bind) {
        Function.prototype.bind = function (oThis) {
            if (typeof this !== "function") {
                throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
            }
            var aArgs = Array.prototype.slice.call(arguments, 1),
                fToBind = this,
                fNOP = function () {
                },
                fBound = function () {
                    return fToBind.apply(this instanceof fNOP && oThis ? this : oThis,
                        aArgs.concat(Array.prototype.slice.call(arguments)));
                };
            fNOP.prototype = this.prototype;
            fBound.prototype = new fNOP();
            return fBound;
        };
    }


    var getInstance = function () {
        var res = {};
        return function (fn) {
            if (res[fn]) {
                return res[fn]
            }
            res[fn] = fn.call(this, arguments)
            return res[fn];
        }
    }
    ()

    var defalutColor = ["007acc", 'a5df12', 'eaaae4', '04d4d4', 'd32311'];

    (function () {
        _.forEach($(".eventBox"), function (item, index) {
            $(item).css("backgroundColor", "#" + defalutColor[index])
        })
    })()


    function rgbToHex(rgb) {
        var _rgb = rgb.match(/[^\(\)]+(?=\))/g)[0].split(",");
        if (_rgb) {
            var hex = "";
            _.forEach(_rgb, function (item) {
                hex += ("0" + parseInt(item, 10).toString(16)).slice(-2);
            })
            return (hex);
        }
    }


    function createCoverDiv() {
        var fixedDiv = document.createElement("div");
        $(fixedDiv).addClass("fixBGDiv").attr("id", "fixedDiv").css("opacity", 0.4);
        $("body").append(fixedDiv)
        return "createCoverDivCall";
    }


    function createPopUpBox() {
        var popUpBoxString = '\
               <div class="modal" id="modalDiv" data-number="">\
                 <div class="modal-dialog">\
                     <div class="modal-content">\
                        <div class="modal-header">\
                            <h4 class="modal-title"><%=modalHeaderTitile%></h4>\
                        </div>\
                        <div class="modal-body">\
                          <div class="time-div">\
                            <div class="time-start-div">\
                               <label class="time-lab"><%=timeStartLab%></label>\
                               <input type="text" maxlength="2" id="startH" class="time-input"> :\
                               <input type="text" maxlength="2" id="startM" class="time-input">\
                            </div>\
                            <div class="time-stop-div">\
                               <label class="time-lab"><%=timeStopLab%></label>\
                               <input type="text" maxlength="2" id="stopH" class="time-input"> :\
                               <input type="text" maxlength="2" id="stopM" class="time-input">\
                            </div>\
                          </div>\
                          <div class="event-div">\
                            <label class="event-lab"><%=eventLab%></label>\
                            <select class="event-select" id="eventSelect">\
                              <%_.forEach(eventArray,function(item,index){%>\
                                <option value=<%=index+1%>><%=item%></option>\
                              <%})%>\
                            </select>\
                          </div>\
                        </div>\
                        <div class="modal-footer">\
                          <button class="btn" id="setBtn"><%=setBtnName%></button>\
                          <button class="btn" id="delBtn"><%=delBtnName%></button>\
                          <button class="btn" id="calBtn"><%=calBtnName%></button>\
                        </div>\
                     </div>\
                 </div>\
               </div>';

        var lan = _gLanguage;
        var obj = {
            'modalHeaderTitile': ["Edit", ""][lan],
            'timeStartLab': ["Start Time", ""][lan],
            'timeStopLab': ["Stop Time", ""][lan],
            'eventLab': ["Event Type", ""][lan],
            'eventArray': [["Event1", ""][lan], ["Event2", ""][lan], ["Event3", ""][lan], ["Event4", ""][lan], ["Event5", ""][lan]],
            'setBtnName': ["Set", ""][lan],
            'delBtnName': ["Delete", ""][lan],
            'calBtnName': ["Cancel", ""][lan]
        }
        popUpBoxString = _.template(popUpBoxString);
        var dom = popUpBoxString(obj);
        $("body").append(dom);

        /*绑定事件*/
        $("#startH,#startM,#stopH,#stopM").keyup(_.debounce(function () {
            var id = this.id;
            var maxTime;
            if (id == "startH") {
                maxTime = 23;
            } else if (id == "stopH") {
                maxTime = 24;
            } else {
                maxTime = 59;
            }
            var regStatus = (/\D+/g).test($(this).val());
            var val = $(this).val().replace(/^0+|\D+/g, "");
            if (!val || regStatus || val < 0 || val > maxTime) {
                $(this).val(0);
            }
        }, 500))

        $("#setBtn").click(function () {
            var contextIndex = $("#modalDiv").attr("data-number") - 1;
            var self = _gContextArray[contextIndex];
            var STH = parseInt($("#startH").val(), 10);
            var STM = parseInt($("#startM").val(), 10);
            var SPH = parseInt($("#stopH").val(), 10);
            var SPM = parseInt($("#stopM").val(), 10);

            if (SPH < STH || (SPH == 24 && SPM > 0)) {
                alert(["Please fill in the correct time", ""][lan]);
                return;
            } else if ((STH == SPH) && (SPM < STM)) {
                alert(["Please fill in the correct time", ""][lan]);
                return;
            } else if ((SPH * 60 + SPM) - (STH * 60 + STM) < 30) {
                alert(["At least 30 minutes apart", ""][lan]);
                return;
            }
            var rightShowId = $("#" + self.timeBlockId_present).children(".rightShow").attr("id");
            var leftShowId = $("#" + self.timeBlockId_present).children(".leftShow").attr("id");

            var newLeft = parseFloat((STH * self.oneHourWidth + STM * self.oneMinWidth).toFixed(4));
            var newRight = parseFloat((SPH * self.oneHourWidth + SPM * self.oneMinWidth).toFixed(4));
            var arrayLen;
            arrayLen = self.left_array.length;
            var tmpLeft = self.left_array.splice(self.whichOne, 1);
            var tmpRight = self.right_array.splice(self.whichOne, 1);
            if (arrayLen >= 2) {
                for (var j = 0; j < arrayLen; j++) {
                    if (newRight > self.left_array[j] && newLeft < self.right_array[j]) {
                        alert(["Coincides with other time periods, please reset", "，"][lan]);
                        self.left_array.push(tmpLeft[0]);
                        self.right_array.push(tmpRight[0]);
                        self.left_array.sort(function (a, b) {
                            return a - b;
                        });
                        self.right_array.sort(function (a, b) {
                            return a - b;
                        });
                        return;
                    }
                }
            }
            self.left_array.push(newLeft);
            self.right_array.push(newRight);
            self.left_array.sort(function (a, b) {
                return a - b;
            });
            self.right_array.sort(function (a, b) {
                return a - b;
            });
            $("#" + self.timeBlockId_present).css({
                "left": newLeft,
                "width": parseFloat((newRight - newLeft).toFixed(4))
            })

            self.setSliderTime(newLeft, leftShowId);
            self.setSliderTime(newRight, rightShowId);


            self.leftTime_array.splice(self.whichOne, 1, self.leftTime);
            self.rightTime_array.splice(self.whichOne, 1, self.rightTime);


            $("#" + self.timeBlockId_present).css("backgroundColor", "#" + defalutColor[$("#eventSelect").val() - 1]);
            self.events_array.splice(self.whichOne, 1, $("#eventSelect").val() - 1);
            $("#modalDiv").hide();
            $("#fixedDiv").hide();
        })

        $("#delBtn").click(function () {
            var contextIndex = $("#modalDiv").attr("data-number") - 1;
            var self = _gContextArray[contextIndex];
            self.right_array.splice(self.whichOne, 1);
            self.left_array.splice(self.whichOne, 1);
            self.leftTime_array.splice(self.whichOne, 1);
            self.rightTime_array.splice(self.whichOne, 1);
            self.events_array.splice(self.whichOne, 1);
            $("#" + self.timeBlockId_present).remove();
            $("#modalDiv").hide();
            $("#fixedDiv").hide();
        })

        $("#calBtn").click(function () {
            $("#modalDiv").hide();
            $("#fixedDiv").hide();
        })
        return "createPopUpBoxCall";
    }

    function creatEditDiv(context) {
        var editDivString = '\
           /* <div class="editWrap" id=<%="editDiv"+context.timeSliderNum%>>\
                <img src="images/edit.png" class="editImg"></img>\
                <img src="images/del.png" class="delImg"></img>\
                <div class="editContent" id=<%="editContent"+context.timeSliderNum%>>\
                    <div class="editHeader">\
                        <label class="editHeaderTitle"><%=editHeaderTitle%></label>\
                    </div>\
                    <div class="editBody">\
                      <%_.forEach(editTextObj,function(item,index){%>\
                          <div class="editUnit">\
                             <input class=<%="editCBox"+context.timeSliderNum%> id=<%="editCBox"+context.timeSliderNum+"_"+index%> type="checkbox"></input>\
                             <label><%=item%></label>\
                          </div>\
                      <%})%>\
                           <div class="editUnit">\
                              <input class="editCheckAll" type="checkbox"></input>\
                              <label><%=checkAllName%></label>\
                           </div>\
                    </div>\
                    <div class="editFotter">\
                        <button class="editBtn save"><%=saveName%></button>\
                        <button class="editBtn cancel"><%=cancelName%></button>\
                    </div>\
                </div>\
            </div>';

        var lan = _gLanguage;
        var editTextObj = [
            ["One.", "1"][lan],
            ["Two.", "2"][lan],
            ["Three.", "3"][lan],
            ["Four.", "4"][lan],
            ["Five.", "5"][lan],
            ["Six.", "6"][lan],
            ["Seven.", "7"][lan]
        ]
        var obj = {
            'context': context,
            'editHeaderTitle': ["Copy To", "Copy To"][lan],
            'editTextObj': editTextObj,
            'checkAllName': ["Check All", "All"][lan],
            'saveName': ["Save", "Save"][lan],
            'cancelName': ["Cancel", "Cancel"][lan]
        }

        editDivString = _.template(editDivString);
        var dom = editDivString(obj);
        $("#" + context.mountId).after(dom);

        $("#editDiv" + context.timeSliderNum).click(function (e) {
            if ($(e.target).attr("class")) {
                if ($(e.target).attr("class") == "editImg") {

                    $(".editContent").hide();
                    $(".editUnit>input[type=checkbox],.editCheckAll").prop("checked", false);
                    $(".editContent").hide();
                    $("#editCBox" + context.timeSliderNum + "_" + (context.timeSliderNum - 1)).attr("disabled", true);
                    $("#editContent" + context.timeSliderNum).show();
                } else if ($(e.target).attr("class") == "delImg") {
                    if (window.confirm(["Do you want to delete all time periods on this timeline?", ""][lan])) {
                        removeAll(context);
                    }
                } else if ($(e.target).attr("class").substring(0, 8) == "editCBox") {
                    $("#editDiv" + context.timeSliderNum + " .editCheckAll").prop("checked", $(".editCBox" + context.timeSliderNum).length == $(".editCBox" + context.timeSliderNum).filter(":checked").length - 1);
                } else if ($(e.target).attr("class") == "editBtn save") {
                    copyTimeSlider(1, context);
                    $("#editContent" + context.timeSliderNum).hide();
                } else if ($(e.target).attr("class") == "editBtn cancel") {
                    $("#editContent" + context.timeSliderNum).hide();
                } else if ($(e.target).attr("class") == "editCheckAll") {
                    var state = $("#editDiv" + context.timeSliderNum + " .editCheckAll").prop("checked");
                    $(".editCBox" + context.timeSliderNum).filter(function (index, item) {
                        if (!$(item).prop("disabled")) {
                            $(item).prop("checked", state)
                        }
                    })
                }
            }
        })
    }

    function copyTimeSlider(flag, context) {
        var self = context;
        var len = self.left_array.length;
        for (var j = 0; j < 7; j++) {
            if ($("#editCBox" + self.timeSliderNum + "_" + j).prop("checked") == true) {
                var targetId = $(".trCanvas").eq(j).parent().attr("id");
                if (flag) {
                    removeAll(_gContextArray[j]);
                }
                for (var i = 0; i < len; i++) {

                    _gContextArray[j].createTimeBlock({
                        backgroundDiv: targetId,
                        offsetX_left: self.left_array[i],
                        offsetX_right: self.right_array[i],
                        event: self.events_array[i]
                    })
                    _gContextArray[j].events_array[i] = self.events_array[i];
                }
            }
        }
    }

    function removeAll(context) {
        var self = context;
        var len = self.right_array.length;
        $("#timeslider" + self.timeSliderNum + " .timeSliderDiv").remove();
        self.right_array.splice(0, len);
        self.left_array.splice(0, len);
        self.rightTime_array.splice(0, len);
        self.leftTime_array.splice(0, len);
        self.events_array.splice(0, len);
        self.timeBlockNum = 0;
        self.leftTime = 0;
        self.rightTime = 0;
    }

    /****************end******************/

    TimeSlider.prototype.constructor = TimeSlider;
    window.TimeSlider = TimeSlider;
})()

function allowDrop(ev) {
    ev.preventDefault();
}
let isDragUnset = false;
let SendData;
let SendTourIdData;


function drag(ev) {
    SendTourIdData = $(ev.path).first().data('tour_id');
    ev.dataTransfer.setData("text", ev.target.id);
    isDragUnset = false;
}

function dragUnset(el) {
    SendTourIdData =  $(el).data('tour_id');
    isDragUnset = true;
    console.log(SendTourIdData);
}


function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    let timeSliderBus = '';

    if (ev.path[1].id) timeSliderBus = ev.path[1].id;
    else timeSliderBus = ev.path[4].id;
    SendData = {'id' : SendTourIdData, 'timeSliderBus' : timeSliderBus};

    $.post(SendUrl, SendData, function (data) {
        console.log(data);
        if (data.result == 'error') {
            toastr.error(data.message);
            $('.container-fluid').html(data.view);
        }
        else {
            toastr.success('данные успешно обновлены');
            $('.container-fluid').html(data.view);
        }
    } );

    if (isDragUnset) console.log(ev.path[1].id);
    else  ev.target.appendChild(document.getElementById(data));
}

function drop2(ev) {
    ev.preventDefault();
    console.log(SendTourIdData);
    SendData = {'id' : SendTourIdData, 'bus_id' : null, 'getView': 'yes'};
    $.post( SendUrl, SendData, function( data ) {
        console.log(data);
        if (data.result == 'error') {
            toastr.error(data.message);
            $('.container-fluid').html(data.view);
        }
        else {
            toastr.success('данные успешно обновлены');
            $('.container-fluid').html(data.view);
        }
    } );
}

function allowDrop2(ev) {
    ev.preventDefault();
}


//
// var message="Правый клик запрещен!";
// ///////////////////////////////////
// function clickIE4(){
//     if (event.button==2){
//         alert(message);
//         return false;
//     }
// }
// function clickNS4(e){
//     if (document.layers||document.getElementById&&!document.all){
//         if (e.which==2||e.which==3){
//             alert(message);
//             return false;
//         }
//     }
// }
// if (document.layers){
//     document.captureEvents(Event.MOUSEDOWN);
//     document.onmousedown=clickNS4;
// }
// else if (document.all&&!document.getElementById){
//     document.onmousedown=clickIE4;
// }
// document.oncontextmenu=new Function("return false")
