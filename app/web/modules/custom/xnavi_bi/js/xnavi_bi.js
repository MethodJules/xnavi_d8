(function ($,Drupal, drupalSettings) {
    var initialized;
    var basePath = drupalSettings.baseUrl;
    var chartType = drupalSettings.chartType;
    var vocabulary = drupalSettings.vocabulary;
    function init() {
        if(!initialized) {
            initialized = true;
            console.log('C3...');
            console.log(chartType);


            //change type of chart
            switch (chartType) {
                case 'normal':
                    var chart = c3.generate({
                        bindto: '#chart',
                        data: {
                            url: basePath + '/xnavi_bi/data',
                            mimeType: 'json',
                        }
                        ,
                        axis: {
                            y: {
                                label: {
                                    text: 'Y Label',
                                    position: 'outer-middle',
                                }
                            }
                        }
                    });
                    break;

                case 'spline':
                    var chart = c3.generate({
                        bindto: '#chart',
                        data: {
                            url: basePath + '/xnavi_bi/data',
                            mimeType: 'json',
                            type: 'spline'
                        }
                        ,
                        axis: {
                            y: {
                                label: {
                                    text: 'Y Label',
                                    position: 'outer-middle',
                                }
                            }
                        }
                    });
                    break;


                case 'regions':
                    var chart = c3.generate({
                        bindto: '#chart',
                        data: {
                            url: basePath + '/xnavi_bi/data',
                            mimeType: 'json',
                            regions: {
                                'data1': [{'start':1, 'end':2, 'style':'dashed'},{'start':3}], // currently 'dashed' style only
                                'data2': [{'end':3}]
                            }
                        }
                    });
                    break;

                case 'step_chart':
                    var chart = c3.generate({
                        bindto: '#chart',
                        data: {
                            url: basePath + '/xnavi_bi/data',
                            mimeType: 'json',
                            types: {
                                data1: 'step',
                                data2: 'area-step'
                            }
                        }
                    });
                    break;

                case 'chart_area':
                    var chart = c3.generate({
                        bindto: '#chart',
                        data: {
                            columns: [
                                ['data1', 300, 350, 300, 0, 0, 0],
                                ['data2', 130, 100, 140, 200, 150, 50]
                            ],
                            types: {
                                data1: 'area',
                                data2: 'area-spline'
                            }
                        }
                    });
                    break;

                case 'bar':
                    var chart = c3.generate({
                        bindto: '#chart',
                        data: {
                                url: basePath + '/xnavi_bi/data/' + vocabulary ,
                                mimeType: 'json',
                                type: 'bar'
                        },
                        bar: {
                            width: {
                                ratio: 0.5 // this makes bar width 50% of length between ticks
                            }
                            // or
                            //width: 100 // this makes bar width 100px
                        }
                    });
                    break;

                case 'pie':
                    var chart = c3.generate({
                        bindto: '#chart',
                        data: {
                            url: basePath + '/xnavi_bi/data/' + vocabulary,
                            mimeType: 'json',
                            type : 'pie',
                            onclick: function (d, i) { console.log("onclick", d, i); },
                            onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                            onmouseout: function (d, i) { console.log("onmouseout", d, i); }
                        }
                    });

                    var chart2 = c3.generate({
                        bindto: '#chart2',
                        data: {
                            url: basePath + '/xnavi_bi/data/' + 'testvokabular',
                            mimeType: 'json',
                            type : 'pie',
                            onclick: function (d, i) { console.log("onclick", d, i); },
                            onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                            onmouseout: function (d, i) { console.log("onmouseout", d, i); }
                        }
                    });
                    break;

            }




        }
    }

    Drupal.behaviors.xnavi_bi = {
        attach: function (context, settings) {

            init();
        }
    }
} (jQuery, Drupal, drupalSettings));