function equalHeights(className) {
    var findClass = document.getElementsByClassName(className);
    var tallest = 0;
  // Loop over matching divs
  for(i = 0; i < findClass.length; i++)
  {
    var ele = findClass[i];
    var eleHeight = ele.offsetHeight;
    tallest = (eleHeight>tallest ? eleHeight : tallest); /* look up ternary operator if you dont know what this is */
  }
  for(i = 0; i < findClass.length; i++)
  {
    findClass[i].style.height = tallest + "px";
  }
}
