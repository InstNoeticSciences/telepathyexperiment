// NAME         -> scrollToBottom()
// PARAMETERS   -> textAreaElement: a text area element ID
// DESCRIPTION  -> scrolls to the bottom of the text area element
function scrollToBottom(textAreaElement) {
    var obj = document.getElementById(textAreaElement);
    obj.scrollTop = obj.scrollHeight - obj.clientHeight;
} // end scrollToBottom()

// NAME         -> showDiv()
// PARAMETERS   -> divElement: a DIV element ID
// DESCRIPTION  -> shows the DIV element
function showDiv(divElement) {
    var obj = document.getElementById(divElement);
    obj.style.display = '';
} // end showDiv()

// NAME         -> hideDiv()
// PARAMETERS   -> divElement: a DIV element ID
// DESCRIPTION  -> hides the DIV element
function hideDiv(divElement) {
    var obj = document.getElementById(divElement);
    obj.style.display = 'none';
} // end showDiv()

