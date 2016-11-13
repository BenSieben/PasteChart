//chart.js
/**
 * Defines a class useful for making point and line graph charts.
 *
 * Example use:
 * graph = new Chart(some_html_element_id,
 *     {"Jan":7, "Feb":20, "Dec":5}, {"title":"Test Chart - Month v Value"});
 * graph.draw();
 *
 * @param String chart_id id of tag that the chart will be drawn into
 * @param Array<Array> data array of sequences [{x_1:[y_1, y_2, ...]}, ... {x_n:[y_1, y_2, ...]}] points to plot
 *    x_i's can be arbitrary labels, y_i's are assumed to be floats
 * @param Object (optional) properties override values for any of the
 *      properties listed in the property_defaults variable below
 */
function Chart(chart_id, data)
{
    var self = this;
    var p = Chart.prototype;
    var properties = (typeof arguments[2] !== 'undefined') ?
        arguments[2] : {};
    var container = document.getElementById(chart_id);
    if (!container) {
        return false;
    }
    var property_defaults = {
        'axes_color' : 'rgb(128,128,128)', // color of the x and y axes lines
        'caption' : '', // caption text appears at bottom
        'caption_style' : 'font-size: 14pt; text-align: center;', // CSS styles to apply to caption text
        //colors used to draw graph (different for each value set)
        'data_colors' : ['rgb(0,0,255)', 'rgb(51,204,51)', 'rgb(255,0,0)', 'rgb(204,0,255)', 'rgb(153,102,51)'],
        'height' : 500, //height of area to draw into in pixels
        'line_width' : 1, // width of line in line graph
        'x_padding' : 30, //x-distance left side of canvas tag to y-axis
        'y_padding' : 30, //y-distance bottom of canvas tag to x-axis
        'point_radius' : 3, //radius of points that are plot in point graph
        'tick_length' : 10, // length of tick marks along axes
        'ticks_y' : 5, // number of tick marks to use for the y axis
        'tick_font_size' : 10, //size of font to use when labeling ticks
        'title' : '', // title text appears at top
        'title_style' : 'font-size:24pt; text-align: center;', // CSS styles to apply to title text
        'type' : 'LineGraph', // can be LineGraph, PointGraph, or Histogram
        'width' : 700 //width of area to draw into in pixels
    };
    for (var property_key in property_defaults) {
        if (typeof properties[property_key] !== 'undefined') {
            this[property_key] = properties[property_key];
        } else {
            this[property_key] = property_defaults[property_key];
        }
    }
    title_tag = (this.title) ? '<div style="' + this.title_style
    + 'width:' + this.width + '" >' + this.title + '</div>' : '';
    caption_tag = (this.caption) ? '<figcaption style="' + this.caption_style
    + 'width:' + this.width + '" >' + this.caption + '</figcaption>' : '';
    container.innerHTML = '<figure>'+ title_tag + '<canvas id="' + chart_id +
        '-content" ></canvas>' + caption_tag + '</figure>';
    canvas = document.getElementById(chart_id + '-content');
    if (!canvas || typeof canvas.getContext === 'undefined') {
        return
    }
    var context = canvas.getContext("2d");
    canvas.width = this.width;
    canvas.height = this.height;
    this.data = data;
    this.data_keys = Object.keys(data); // all labels in the data
    this.num_graphs =  data[this.data_keys[0]].length; // number of graph(s) that will be drawn based on given data
    /**
     * Main function used to draw the graph type selected
     */
    p.draw = function()
    {
        // to handle k-tuples instead of 2-tuples, we must draw each data
        //   set, one at a time
        for(var i = 0; i < self.num_graphs; i++) {
            self['draw' + self.type](i); // i = index of each values array to pull data from when graphing this time
        }
    };
    /**
     * Used to store in fields the min and max y values as well as the start
     * and end x keys, and the range = max_y - min_y
     */
    p.initMinMaxRange = function()
    {
        self.min_value = null;
        self.max_value = null;
        self.start;
        self.end;
        var key;
        for (key in data) { // for each label..
            for(var i = 0; i < self.num_graphs; i++) { // for each value in each label...
                if(data[key][i] !== null) { // data[key][i] is null when we have a gap in information, so don't check those for max  min
                    if (self.min_value === null) {
                        self.min_value = data[key][i];
                        self.max_value = data[key][i];
                        self.start = key;
                    }
                    if (data[key][i] < self.min_value) {
                        self.min_value = data[key][i];
                    }
                    if (data[key][i] > self.max_value) {
                        self.max_value = data[key][i];
                    }
                }
            }
        }
        self.end = key;
        self.range = self.max_value - self.min_value;
    };
    /**
     * Used to draw a point at location x,y in the canvas
     */
    p.plotPoint = function(x,y)
    {
        var c = context;
        c.beginPath();
        c.arc(x, y, self.point_radius, 0, 2 * Math.PI, true);
        c.fill();
    };
    /**
     * Draws the x and y axes for the chart as well as ticks marks and values
     */
    p.renderAxes = function()
    {
        var c = context;
        var height = self.height - self.y_padding;
        c.strokeStyle = self.axes_color;
        c.lineWidth = self.line_width;
        c.beginPath();
        c.moveTo(self.x_padding - self.tick_length,
            self.height - self.y_padding);
        c.lineTo(self.width - self.x_padding,  height);  // x axis
        c.stroke();
        c.beginPath();
        c.moveTo(self.x_padding, self.tick_length);
        c.lineTo(self.x_padding, self.height - self.y_padding +
            self.tick_length);  // y axis
        c.stroke();
        var spacing_y = self.range/self.ticks_y;
        height -= self.tick_length;
        var min_y = parseFloat(self.min_value);
        var max_y = parseFloat(self.max_value);
        var num_format = new Intl.NumberFormat("en-US",
            {"maximumFractionDigits":2});
        // Draw y ticks and values
        for (var val = min_y; val < max_y + spacing_y; val += spacing_y) {
            y = self.tick_length + height *
                (1 - (val - self.min_value)/self.range);
            c.font = self.tick_font_size + "px serif";
            c.fillText(num_format.format(val), 0, y + self.tick_font_size/2,
                self.x_padding - self.tick_length);
            c.beginPath();
            c.moveTo(self.x_padding - self.tick_length, y);
            c.lineTo(self.x_padding, y);
            c.stroke();
        }
        // Draw x ticks and values
        var dx = (self.width - 2 * self.x_padding) /
            (Object.keys(data).length - 1);
        var x = self.x_padding;
        for (var key in data) {
            c.font = self.tick_font_size + "px serif";
            if(key !== "") { // do not draw text if empty string key (this is special histogram key)
                if(self.type === 'Histogram') { // draw x values in slightly different location for histograms
                    c.fillText(key, x - self.tick_font_size/2 * (key.length - 0.5) +
                        ((self.width - 2*self.x_padding) / (Object.keys(data).length - 1) / 2),
                        self.height - self.y_padding +  self.tick_length +
                        self.tick_font_size, self.tick_font_size * (key.length - 0.5));
                }
                else {
                    c.fillText(key, x - self.tick_font_size/2 * (key.length - 0.5),
                        self.height - self.y_padding +  self.tick_length +
                        self.tick_font_size, self.tick_font_size * (key.length - 0.5));
                }
            }
            c.beginPath();
            c.moveTo(x, self.height - self.y_padding + self.tick_length);
            c.lineTo(x, self.height - self.y_padding);
            c.stroke();
            x += dx;
        }
    };
    /**
     * Draws a chart consisting of just x-y plots of points in data.
     * dataIndex = which index of data to get information from
     */
    p.drawPointGraph = function(dataIndex)
    {
        if(dataIndex === 0) { // only find range / draw axes for first set of data
            self.initMinMaxRange();
            self.renderAxes();
        }
        var dx = (self.width - 2*self.x_padding) /
            (Object.keys(data).length - 1);
        var c = context;
        c.lineWidth = self.line_width;
        c.strokeStyle = self.data_colors[dataIndex];
        c.fillStyle = self.data_colors[dataIndex];
        var height = self.height - self.y_padding - self.tick_length;
        var x = self.x_padding;
        for (var key in data) {
            if(data[key][dataIndex] !== null) { // do not draw null dots (places that did not have value specified)
                var y = self.tick_length + height *
                    (1 - (data[key][dataIndex] - self.min_value)/self.range);
                self.plotPoint(x, y);
            }
            x += dx;
        }
    };
    /**
     * Draws a chart consisting of x-y plots of points in data, each adjacent
     * point pairs connected by a line segment
     * dataIndex = which index of data to get information from
     */
    p.drawLineGraph = function(dataIndex)
    {
        self.drawPointGraph(dataIndex);
        var c = context;
        c.beginPath();
        var x = self.x_padding;
        var dx =  (self.width - 2*self.x_padding) /
            (Object.keys(data).length - 1);
        var height = self.height - self.y_padding  - self.tick_length;
        c.moveTo(x, self.tick_length + height * (1 -
            (data[self.start] - self.min_value)/self.range));
        for (var key in data) {
            if(data[key][dataIndex] !== null) { // do not draw null lines (places that did not have value specified)
                var y = self.tick_length + height *
                    (1 - (data[key][dataIndex] - self.min_value) / self.range);
                c.lineTo(x, y);
            }
            x += dx;
        }
        c.stroke();
    };
    /**
     * Draws a chart consisting of x-y plots of bars in data
     * dataIndex = which index of data to get information from
     */
    p.drawHistogram = function(dataIndex)
    {
        // when drawing histogram, add extra property to data to give space for all drawn elements
        data[""] = [];
        for(var i = 0;  i < data[self.data_keys[0]].length; i++) { // all of this new label's y-values are null
            data[""].push(null);
        }
        self.data_keys = Object.keys(data); // reset all labels in the data
        if(dataIndex === 0) { // only find range / draw axes for first set of data
            self.initMinMaxRange();
            // the min value is lowered because this makes sure all filled y-values have at least a little bit of height
            self.min_value -= (self.range * 0.1);
            self.range = self.max_value - self.min_value;
            self.renderAxes();
        }
        var c = context;
        c.fillStyle = self.data_colors[dataIndex]; // fill in color depends on dataIndex
        c.strokeStyle = self.axes_color; // all bars get outlined in the same color as axes
        c.lineWidth = self.line_width; // set up width for drawing outlines (for when it is not already set)
        c.beginPath();
        var dx = (self.width - 2*self.x_padding) / (Object.keys(data).length - 1);
        var barWidth = dx / self.num_graphs; // how wide each bar should be depends on number of drawn graphs
        var x = self.x_padding + (barWidth * dataIndex);
        var height = self.height - self.y_padding;
        for(var key in data) {
            if(data[key][dataIndex] !== null) {  // do not draw null bars (bars that did not have value specified)
                var y = self.tick_length + height *
                    (1 - (data[key][dataIndex] - self.min_value)/self.range);
                c.fillRect(x, y, barWidth, Math.max((height - y), 0)); // math.max because at least one bar will always have 0 length
                c.rect(x, y, barWidth, Math.max((height - y), 0)); // draw outline for bar
            }
            x += dx;
        }
        c.stroke();
    }
}