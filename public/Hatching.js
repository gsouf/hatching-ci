var HatchingCi = {};


$(function(){

    HatchingCi.updateTimes(".auto-update-date");

    setInterval(function(){
        HatchingCi.updateTimes(".auto-update-date");
    },60000); //update the time every minute

});

/**
 * this function should be triggered every minute then update all the spans with the class updatable-hr-time
 */
HatchingCi.updateTimes = function(selector){

    var now = new Date().getTime()/1000;

    var oneHour = 3600;
    var oneDay = 86400;
    var oneWeek = 604800;
    var oneMonth = 2329744;
    var oneYear = 31556926;

    $(selector).each(function(){

        var span = $(this);
        var alreadyUpdated=false;



        if(!span.attr("ph_basetime")){
            span.attr("ph_basetime",HatchingCi.dateFromMysqlString(span.html()).toString() );
        }

        if(reupdate = span.attr("ph_reupdatetime") && reupdate==0)
            return;


        var date= new Date(span.attr("ph_basetime"));
        var dateTime= date.getTime()/1000;


        // more than one year
        if(now - dateTime > oneMonth ){
            span.html( date.getDate() + " " + PhLocale.month[date.getMonth()]  + " " + date.getFullYear());
            span.attr("ph_reupdatetime","0"); //dont need to be reupdated after 1 year
        }
        // more than one week
        else if(now - dateTime > oneWeek ){
            span.html( HatchingCi.Locale.days[date.getDay()] + " " + date.getDate() + " " + HatchingCi.Locale.month[date.getMonth()] );
            span.attr("ph_reupdatetime","0"); //dont need to be reupdated after 1 week
        }
        // more than a day
        else if(now - dateTime > oneDay ){
            span.html( HatchingCi.Locale.days[date.getDay()] + " " + date.getHours() + ":" + date.getMinutes() );
            span.attr("ph_reupdatetime","0"); //dont need to be reupdated after 1 day
        }
        // more than one hour
        else if(now - dateTime > oneHour ){
            span.html( Math.round((now - dateTime) / 3600 ) +  ' hours ago' );
        }
        // less than one hour
        else{
            span.html( Math.round((now - dateTime) / 60 ) +  ' minutes ago' );
        }

    });
}


/**
 * convert a mysql date time to a javascript date
 * source : http://stackoverflow.com/questions/3075577/convert-mysql-datetime-stamp-into-javascripts-date-format
 * @returns {*}
 */
HatchingCi.dateFromMysqlString = function(mysql_string){
    if(typeof mysql_string === 'string')
    {
        var t = mysql_string.split(/[- :]/);

        return new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);
    }

    return null;
}