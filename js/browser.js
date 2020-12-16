// browser determination
function get_browser() {
    var agent = navigator.userAgent.toLowerCase();

    // firefox
    if(agent.indexOf("firefox") != -1) {
        return "FIREFOX";
    } // end if

    // internet explorer
    if(agent.indexOf("msie") != -1) {
        return "MSIE";
    } // end if
    
    // safari
    if(agent.indexOf("safari") != -1) {
        return "SAFARI";
    } // end if

    // other browsers not yet supported
    return "UNKNOWN";
} // end get_browser()


