var xmlHttp = createXmlHttpRequestObject();

var xsltFileUrl = "";
var feedGridUrl = "";

var dataSourceId = "";
var statusDivId = "";
var gridDivId = "";
var dataFilter = "";

var tempRow;
var editableId = null;
var stylesheetDoc;

// entry point for the grid functionality
function init(xsltFile, feedGrid, statusDiv, gridDiv, dataSource, dFilter) {
    // test if user has browser that supports native XSLT functionality
    if(window.XMLHttpRequest && window.XSLTProcessor && window.DOMParser) {
        // set the source, URL and DIV parameters
        dataSourceId = dataSource;
        xsltFileUrl = xsltFile;
        feedGridUrl = feedGrid;
        statusDivId = statusDiv;
        dataFilter = dFilter;
        gridDivId = gridDiv;

        // load the stylesheet and the grid
        loadStylesheet();
        loadGridPage(1);

        // exit the function
        return;
    } // end if

    // test if user has Internet Explorer with proper XSLT support
    if (window.ActiveXObject && createMsxml2DOMDocumentObject()) {
        // load the stylesheet and the grid
        loadStylesheet();
        loadGridPage(1);

        // exit the function
        return;
    } // end if

    // if browser functionality testing failed, alert the user
    alert("Your browser doesn't support the necessary functionality.");
} // end init()

function createMsxml2DOMDocumentObject() {
    // will store the reference to the MSXML object
    var msxml2DOM;
    // MSXML versions that can be used for our grid
    var msxml2DOMDocumentVersions = new Array("Msxml2.DOMDocument.6.0",
                                              "Msxml2.DOMDocument.5.0",
                                              "Msxml2.DOMDocument.4.0");

    // try to find a good MSXML object
    for (var i=0; i<msxml2DOMDocumentVersions.length && !msxml2DOM; i++) {
        try {
            // try to create an object
            msxml2DOM = new ActiveXObject(msxml2DOMDocumentVersions[i]);
        } catch (e) {
        } // end try-catch
    } // end for

    // return the created object or display an error message
    if (!msxml2DOM) {
        alert("Please upgrade your MSXML version from \n" +
              "http://msdn.microsoft.com/XML/XMLDownloads/default.aspx");
        return null;
    } else {
        return msxml2DOM;
    } // end if
} // end createMsxml2DOMDocumentObject()

// creates an XMLHttpRequest instance
function createXmlHttpRequestObject() {
    // will store the reference to the XMLHttpRequest object
    var xmlHttp;

    // this should work for all browsers except IE6 and older
    try {
        // try to create XMLHttpRequest object
        xmlHttp = new XMLHttpRequest();
    } catch(e) {
        // assume IE6 or older
        var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
                                        "MSXML2.XMLHTTP.5.0",
                                        "MSXML2.XMLHTTP.4.0",
                                        "MSXML2.XMLHTTP.3.0",
                                        "MSXML2.XMLHTTP",
                                        "Microsoft.XMLHTTP");

        // try every prog id until one works
        for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) {
            try  {
                // try to create XMLHttpRequest object
                xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
            } catch (e) {
            } // end try-catch
        } // end for
    } // end try-catch

    // return the created object or display an error message
    if (!xmlHttp) {
        alert("Error creating the XMLHttpRequest object.");
        return null;
    } else {
        return xmlHttp;
    } // end if
} // createXmlHttpRequestObject()

// loads the stylesheet from the server using a synchronous request
function loadStylesheet() {
    // load the file from the server
    xmlHttp.open("GET", xsltFileUrl, false);
    xmlHttp.send(null);

    // try to load the XSLT document
    if (this.DOMParser) {
        var dp = new DOMParser();
        stylesheetDoc = dp.parseFromString(xmlHttp.responseText, "text/xml");
    } else if (window.ActiveXObject) {
        stylesheetDoc = createMsxml2DOMDocumentObject();
        stylesheetDoc.async = false;
        stylesheetDoc.load(xmlHttp.responseXML);
    } // end if
} // end loadStylesheet()

// makes asynchronous request to load a new page of the grid
function loadGridPage(pageNo) {
    // disable edit mode when loading new page
    editableId = false;

    // continue only if the XMLHttpRequest object isn't busy
    if (xmlHttp && (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)) {
        var query = feedGridUrl + "?action=FEED_GRID_PAGE&page=" + pageNo +
                    "&source=" + dataSourceId +
                    "&filter=" + dataFilter;

        xmlHttp.open("GET", query, true);
        xmlHttp.onreadystatechange = handleGridPageLoad;
        xmlHttp.send(null);
    } // end if
} // end loadGridPage()

// handle receiving the server response with a new page of products
function handleGridPageLoad() {
    // when readyState is 4, we read the server response
    if (xmlHttp.readyState == 4) {
        // continue only if HTTP status is "OK"
        if (xmlHttp.status == 200) {
            // read the response
            response = xmlHttp.responseText;

            // server error?
            if (response.indexOf("ERRNO") >= 0 ||
                response.indexOf("error") >= 0 ||
                response.length == 0) {
                // display error message
                alert(response.length == 0 ? "Server error: " : response);

                // exit function
                return;
            } // end if

            // the server response in XML format
            xmlResponse = xmlHttp.responseXML;

            // browser with native functionality?
            if (window.XMLHttpRequest &&
                window.XSLTProcessor &&
                window.DOMParser) {
                // load the XSLT document
                var xsltProcessor = new XSLTProcessor();
                xsltProcessor.importStylesheet(stylesheetDoc);
                
                // generate the HTML code for the new page of products
                page = xsltProcessor.transformToFragment(xmlResponse, document);

                // display the page of results
                var gridDiv = document.getElementById(gridDivId);
                gridDiv.innerHTML = "";
                gridDiv.appendChild(page);
            } else if (window.ActiveXObject) {
                // load the XSLT document
                var theDocument = createMsxml2DOMDocumentObject();
                theDocument.async = false;
                theDocument.load(xmlResponse);

                // display the page of results
                var gridDiv = document.getElementById(gridDivId);
                gridDiv.innerHTML = theDocument.transformNode(stylesheetDoc);
            } // end if
        } else {
            alert("Error reading server response (" + xmlHttp.status + ")");
        } // end if
    } // end if
} // end handleGridPageLoad()