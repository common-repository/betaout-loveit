/*!
 * wordpress plugin loveit API Library V1
 *
 * Date: april 2011
 * Amit kumar srivastava
 * amit.srivastava@instablogs.com,amee9451@gmail.com
 */
function loveIt(elementId)
{
    var dataString="pid="+elementId+"&action=loveit";
    jQuery.ajax({
        type :'GET',
        url : loveitL10n.ajax_url,
        data :dataString,
        dataType:'json',
        success : function(resp){ 
            jQuery("#likeClass_"+elementId).removeClass('loveIt');
            jQuery("#likeClass_"+elementId).addClass('loveItActive');
            if(document.getElementById('totalLike_'+elementId)==null){
                document.getElementById('likeClass_'+elementId).innerHTML='<span id="totalLike_'+elementId+'" class="totalLikes">'+resp+'</span>'
            }
            else{
                document.getElementById('totalLike_'+elementId).innerHTML="";
                document.getElementById('totalLike_'+elementId).innerHTML=resp;
            }
            try {
                jQuery("#heartbeat").remove();
            }catch(e){}
            jQuery("#likeClass_"+elementId).hide();
            jQuery("#likeClass_"+elementId).attr('title', 'You loved it!');
            jQuery("#likeClass_"+elementId).attr('data-original-title', 'You loved it!');
            jQuery(".twipsy-inner").text('You loved it!');
            jQuery("#likeClass_"+elementId).show();

        }        
    });
}
jQuery(document).ready(function(){
    jQuerycurr=""
    var clear;
    jQuery(".likeitheart").live('mouseover',function () {
        if(jQuery(".likeitheart").hasClass('loveIt')){
            try{
                jQuery("#heartbeat").remove();
            }catch(e){}
            jQuerycurr=jQuery(this);
            var ids=this.id;
            var passData=jQuerycurr.attr('rel-data');
            passData=passData.split("_");
            jQuery("#likeClass_"+passData[0]).attr('title', 'Loving it...');
            jQuery("#likeClass_"+passData[0]).append('<img id="heartbeat" style="height: 51px; top: -33px; left: -6px; position: relative;" src="+loveitL10n.plugin_url+"/image/heart.gif" />');
            clear=setTimeout( function () {   
                //            jQuery("#likeClass_"+passData[0]).attr('title', 'You Loving...');
                //jQuery("#likeClass_"+passData[0]).attr('data-original-title', 'You Loving It!');
                loveIt(passData[0],passData[1]);
                jQuery("#"+ids).removeClass('likeitheart');
            }, 1000); 
        }else{
            clearTimeout(clear);
            jQuery("#heartbeat").remove();
        }
    });
    jQuery(".likeitheart").live('mouseleave',function () {
        clearTimeout(clear);
        jQuery("#heartbeat").remove();
    });
});