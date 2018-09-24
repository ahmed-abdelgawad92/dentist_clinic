function assignError(element,errorMsg) {
  element.addClass("is-invalid");
  element.after("<div style='display:block' class='invalid-feedback'>"+errorMsg+"</div>");
}
function chngTimeTo12(timeString){
  var H = +timeString.substr(0, 2);
  var h = H % 12 || 12;
  var ampm = (H < 12 || H === 24) ? " am" : " pm";
  timeString = h + timeString.substr(2, 3) + ampm;

  return timeString;
}
//validate empty string
function validateNotEmpty(str) {
  if(str.length>0)
  return true;
  else
  return false;
}
//validate admin and patients names
function validateName(name){
  return name.match(/^[a-zA-Z\s_]+$/);
}
//Validate number
function validateNumber(number){
  return number.match(/^[0-9]+(\.?[0-9]+)?$/);
}
//validate a username
function validateUname(uname) {
  return uname.match(/^([a-zA-Z]+([\._@\-]?[0-9a-zA-Z]+)*){3,}$/);
}
//validate a username
function validatePassword(pass) {
  return pass.match(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/);
}
//validate Alphabet
function validateAlphabet(str) {
  return str.match(/[a-zA-Z\s_]+/);
}
//validate Alphabet
function validateTime(time) {
  return time.match(/^[0-9]{2}:[0-9]{2}$/);
}
//validate Date
function validateDate(date){
  return date.match(/^([0-9]{4}\-((01|03|05|07|08|10|12)\-(0[0-9]|1[0-9]|2[0-9]|3[0-1])|(04|06|09|11)\-(0[0-9]|1[0-9]|2[0-9]|30)|(02)\-(0[0-9]|1[0-9]|2[0-9])))$/);
}
//validate phone number
function validatePhone(phone) {
  return phone.match(/^(\+)?[0-9]{8,15}$/);
}
//validate bool enum
function validateBoolEnum(enumValue) {
  return enumValue.match(/^(0|1)$/);
}
//validate bool enum
function validateBloodEnum(enumValue) {
  return enumValue.match(/^(low|normal|high)$/);
}
// validate Photo
function validatePhoto(photo) {
  var ext = photo.split(".").pop().toLowerCase();
  switch (ext) {
    case "png":
      return true;
      break;
    case "jpg":
      return true;
      break;
    case "jpeg":
      return true;
      break;
    case "git":
      return true;
      break;
    default:
      return false;
  }
}
/*****************************************************************************************************************************************/

//show image before uploading it
function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $("#patient_profile_photo").attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}
function getToothName(tooth, color) {
  var tooth_svg = new Array();
  console.log("called");
  switch (tooth) {
    case 1:
      tooth_svg.push("Upper Right Quadrant: Wisdom Tooth (3rd Molar) {{1}}");
      tooth_svg.push('<circle cx="92" cy="324" r="25" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{8}}");
      return tooth_svg;
    break;
    case 2:
      tooth_svg.push("Upper Right Quadrant: Molar (2nd Molar) {{2}}");
      tooth_svg.push('<circle cx="95" cy="274" r="26" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{7}}");
      return tooth_svg;
    break;
    case 3:
      tooth_svg.push("Upper Right Quadrant: Molar (1st Molar) {{3}}");
      tooth_svg.push('<circle cx="102" cy="227" r="26" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{6}}");
      return tooth_svg;
    break;
    case 4:
      tooth_svg.push("Upper Right Quadrant: Bicuspid (2nd) {{4}}");
      tooth_svg.push('<circle cx="115" cy="180" r="25" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{5}}");
      return tooth_svg;
    break;
    case 5:
      tooth_svg.push("Upper Right Quadrant: Bicuspid (1st) {{5}}");
      tooth_svg.push('<circle cx="136" cy="138" r="24" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{4}}");
      return tooth_svg;
    break;
    case 6:
      tooth_svg.push("Upper Right Quadrant: Canine (Eye tooth / Cuspid) {{6}}");
      tooth_svg.push('<circle cx="162" cy="104" r="20" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{3}}");
      return tooth_svg;
    break;
    case 7:
      tooth_svg.push("Upper Right Quadrant: Incisor (Lateral) {{7}}");
      tooth_svg.push('<circle cx="187" cy="76" r="20" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{2}}");
      return tooth_svg;
    break;
    case 8:
      tooth_svg.push("Upper Right Quadrant: Incisor (Central) {{8}}");
      tooth_svg.push('<circle cx="226" cy="57" r="24" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{1}}");
      return tooth_svg;
    break;
    case 9:
      tooth_svg.push("Upper Left Quadrant: Incisor (Central) {{9}}");
      tooth_svg.push('<circle cx="271" cy="57" r="23" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{1}}");
      return tooth_svg;
    break;
    case 10:
      tooth_svg.push("Upper Left Quadrant: Incisor (Lateral) {{10}}");
      tooth_svg.push('<circle cx="311" cy="75" r="24" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{2}}");
      return tooth_svg;
    break;
    case 11:
      tooth_svg.push("Upper Left Quadrant: Canine (Eye tooth / Cuspid) {{11}}");
      tooth_svg.push('<circle cx="337" cy="104" r="21" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{3}}");
      return tooth_svg;
    break;
    case 12:
      tooth_svg.push("Upper Left Quadrant: Bicuspid (1st) {{12}}");
      tooth_svg.push('<circle cx="361" cy="137" r="23" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{4}}");
      return tooth_svg;
    break;
    case 13:
      tooth_svg.push("Upper Left Quadrant: Bicuspid (2nd) {{13}}");
      tooth_svg.push('<circle cx="382" cy="181" r="25" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{5}}");
      return tooth_svg;
    break;
    case 14:
      tooth_svg.push("Upper Left Quadrant: Molar (1st) {{14}}");
      tooth_svg.push('<circle cx="395" cy="226" r="24" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{6}}");
      return tooth_svg;
    break;
    case 15:
      tooth_svg.push("Upper Left Quadrant: Molar (2nd) {{15}}");
      tooth_svg.push('<circle cx="402" cy="275" r="24" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{7}}");
      return tooth_svg;
    break;
    case 16:
      tooth_svg.push("Upper Left Quadrant: Wisdom Tooth (3rd Molar) {{16}}");
      tooth_svg.push('<circle cx="404" cy="323" r="25" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{8}}");
      return tooth_svg;
    break;
    case 17:
      tooth_svg.push("Lower Left Quadrant: Wisdom Tooth (3rd Molar) {{17}}");
      tooth_svg.push('<circle cx="401" cy="397" r="28" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{8}}");
      return tooth_svg;
    break;
    case 18:
      tooth_svg.push("Lower Left Quadrant: Molar (2nd Molar) {{18}}");
      tooth_svg.push('<circle cx="398" cy="451" r="26" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{7}}");
      return tooth_svg;
    break;
    case 19:
      tooth_svg.push("Lower Left Quadrant: Molar (1st Molar) {{19}}");
      tooth_svg.push('<circle cx="388" cy="502" r="27" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{6}}");
      return tooth_svg;
    break;
    case 20:
      tooth_svg.push("Lower Left Quadrant: Bicuspid (2nd) {{20}}");
      tooth_svg.push('<circle cx="370" cy="553" r="27" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{5}}");
      return tooth_svg;
    break;
    case 21:
      tooth_svg.push("Lower Left Quadrant: Bicuspid (1st) {{21}}");
      tooth_svg.push('<circle cx="345" cy="594" r="25" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{4}}");
      return tooth_svg;
    break;
    case 22:
      tooth_svg.push("Lower Left Quadrant: Canine (Eye tooth / Cuspid) {{22}}");
      tooth_svg.push('<circle cx="318" cy="625" r="17" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{3}}");
      return tooth_svg;
    break;
    case 23:
      tooth_svg.push("Lower Left Quadrant: Incisor (Lateral) {{23}}");
      tooth_svg.push('<circle cx="293" cy="642" r="14" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{2}}");
      return tooth_svg;
    break;
    case 24:
      tooth_svg.push("Lower Left Quadrant: Incisor (Central) {{24}}");
      tooth_svg.push('<circle cx="263" cy="649" r="16" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{1}}");
      return tooth_svg;
    break;
    case 25:
      tooth_svg.push("Lower Right Quadrant: Incisor (Central) {{25}}");
      tooth_svg.push('<circle cx="233" cy="648" r="15" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{1}}");
      return tooth_svg;
    break;
    case 26:
      tooth_svg.push("Lower Right Quadrant: Incisor (Lateral) {{26}}");
      tooth_svg.push('<circle cx="202" cy="641" r="17" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{2}}");
      return tooth_svg;
    break;
    case 27:
      tooth_svg.push("Lower Right Quadrant: Canine (Eye tooth / Cuspid) {{27}}");
      tooth_svg.push('<circle cx="179" cy="625" r="19" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{3}}");
      return tooth_svg;
    break;
    case 28:
      tooth_svg.push("Lower Right Quadrant: Bicuspid (1st) {{28}}");
      tooth_svg.push('<circle cx="153" cy="594" r="23" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{4}}");
      return tooth_svg;
    break;
    case 29:
      tooth_svg.push("Lower Right Quadrant: Bicuspid (2nd) {{29}}");
      tooth_svg.push('<circle cx="127" cy="553" r="27" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{5}}");
      return tooth_svg;
    break;
    case 30:
      tooth_svg.push("Lower Right Quadrant: Molar (1st Molar) {{30}}");
      tooth_svg.push('<circle cx="108" cy="503" r="27" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{6}}");
      return tooth_svg;
    break;
    case 31:
      tooth_svg.push("Lower Right Quadrant: Molar (2nd Molar) {{31}}");
      tooth_svg.push('<circle cx="99" cy="451" r="27" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{7}}");
      return tooth_svg;
    break;
    case 32:
      tooth_svg.push("Lower Right Quadrant: Wisdom Tooth (3rd Molar) {{32}}");
      tooth_svg.push('<circle cx="94" cy="397" r="28" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("{{8}}");
      return tooth_svg;
    case 'a':
      tooth_svg.push(" {{A}} ");
      tooth_svg.push('<circle cx="170" cy="226" r="18" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Right {{E}}");
      return tooth_svg;
    case 'b':
      tooth_svg.push(" {{B}} ");
      tooth_svg.push('<circle cx="178" cy="193" r="16" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Right {{D}}");
      return tooth_svg;
    case 'c':
      tooth_svg.push(" {{C}} ");
      tooth_svg.push('<circle cx="193" cy="169" r="16" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Right {{C}}");
      return tooth_svg;
    case 'd':
      tooth_svg.push(" {{D}} ");
      tooth_svg.push('<circle cx="209" cy="147" r="13" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Right {{B}}");
      return tooth_svg;
    case 'e':
      tooth_svg.push(" {{E}} ");
      tooth_svg.push('<circle cx="232" cy="132" r="15" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Right {{A}}");
      return tooth_svg;
    case 'f':
      tooth_svg.push(" {{F}} ");
      tooth_svg.push('<circle cx="263" cy="133" r="16" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Left {{A}}");
      return tooth_svg;
    case 'g':
      tooth_svg.push(" {{G}} ");
      tooth_svg.push('<circle cx="287" cy="147" r="15" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Left {{B}}");
      return tooth_svg;
    case 'h':
      tooth_svg.push(" {{H}} ");
      tooth_svg.push('<circle cx="303" cy="170" r="13" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Left {{C}}");
      return tooth_svg;
    case 'i':
      tooth_svg.push(" {{I}} ");
      tooth_svg.push('<circle cx="318" cy="194" r="17" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Left {{D}}");
      return tooth_svg;
    case 'j':
      tooth_svg.push(" {{J}} ");
      tooth_svg.push('<circle cx="326" cy="227" r="18" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Upper Left {{E}}");
      return tooth_svg;
    case 'k':
      tooth_svg.push(" {{K}} ");
      tooth_svg.push('<circle cx="329" cy="480" r="20" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Left {{E}}");
      return tooth_svg;
    case 'l':
      tooth_svg.push(" {{L}} ");
      tooth_svg.push('<circle cx="317" cy="515" r="19" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Left {{D}}");
      return tooth_svg;
    case 'm':
      tooth_svg.push(" {{M}} ");
      tooth_svg.push('<circle cx="303" cy="549" r="15" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Left {{C}}");
      return tooth_svg;
    case 'n':
      tooth_svg.push(" {{N}} ");
      tooth_svg.push('<circle cx="283" cy="569" r="14" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Left {{B}}");
      return tooth_svg;
    case 'o':
      tooth_svg.push(" {{O}} ");
      tooth_svg.push('<circle cx="260" cy="577" r="14" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Left {{A}}");
      return tooth_svg;
    case 'p':
      tooth_svg.push(" {{P}} ");
      tooth_svg.push('<circle cx="236" cy="577" r="15" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Right {{A}}");
      return tooth_svg;
    case 'q':
      tooth_svg.push(" {{Q}} ");
      tooth_svg.push('<circle cx="211" cy="570" r="13" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Right {{B}}");
      return tooth_svg;
    case 'r':
      tooth_svg.push(" {{R}} ");
      tooth_svg.push('<circle cx="193" cy="548" r="16" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Right {{C}}");
      return tooth_svg;
    case 's':
      tooth_svg.push(" {{S}} ");
      tooth_svg.push('<circle cx="179" cy="517" r="20" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"');
      tooth_svg.push("Lower Right {{D}}");
      return tooth_svg;
    case 't':
      tooth_svg.push(" {{T}} ");
      tooth_svg.push('<circle cx="168" cy="481" r="21" stroke="black" stroke-width="3" fill="'+color+'" opacity="0.7"/>');
      tooth_svg.push("Lower Right {{E}}");
      return tooth_svg;
    break;
  }

}
