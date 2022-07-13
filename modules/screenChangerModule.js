// Create a page and link element for the screen
class Page {
  constructor(name) {
    this.name = name;
    this.navElement = document.createElement("li");
    this.navElement.id = name;
    this.navElement.innerText = name;
  }
  AssignParent (contentParent,cls) {
    this.pageDiv = document.createElement("div");
    this.pageDiv.classList.add("hidden");
    this.pageDiv.classList.add(cls.replace(/[\-," "]/g,""));
    this.pageDiv.id = "divFor"+this.name.replace(/[\-," "]/g,"");
    document.querySelector(contentParent).append(this.pageDiv);
  }
  AddIcon (icon) {
    this.icon = icon;
    // add icon to nav and hi/title
  }
  AddContent (html) {
    this.pageDiv.innerHTML = html;
  }
  Inactivate () {
    console.log(this.pageDiv.classList);
    this.pageDiv.classList.add("hidden");
  }
  Activate () {
    this.pageDiv.classList.remove("hidden");
  }
}
// create a nav that pages are added to
class Nav {
  constructor(name,container){
    this.pages = {};
    this.element = document.createElement("ul");
    this.name = name;
    this.element.id = name;
    this.element.onclick = (e)=>{
      Object.keys(this.pages).forEach((page, i) => {
        this.pages[page].Inactivate();
      });
      this.pages[(e.srcElement.id).replace("divFor","")].Activate();
      history.pushState({},'',location.origin+globalThis["folderName"]+(this.pages[(e.srcElement.id)].name).replace(/[\-," "]/g,""));
    }
    this.AddToDoc(container);
  }
  AddToDoc (parentQuery) {
    this.element.remove();
    document.querySelector(parentQuery).append(this.element);
  }
  AddPage (page) {
    this.pages[page.name] = page;
    this.element.append(page.navElement);
  }
}
//pass a nave and array of page objects and build the app/webpage
function build(nav, allPages, parent) {
  Object.keys(allPages).forEach((pageName, i) => {
    allPages[pageName].nav.AssignParent(parent,parent+"Screen");
    console.log(allPages[pageName].nav,allPages[pageName].content);
    (allPages[pageName].nav).AddContent(allPages[pageName].content);
    nav.AddPage(allPages[pageName].nav);
    if (allPages[pageName].init){
      allPages[pageName].init();
    }
  });
}
export {Nav, Page, build};
