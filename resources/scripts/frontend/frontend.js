import $ from 'jquery'


class PostTitle {

  constructor(text) {
    this.text = text;
  }


  render (){

    return `<h1>${this.text}</h1>`;

  }

}

class PostDescription{
  constructor(text) {
    this.text = text;
  }


  render (){

    return `<p>${this.text}</p>`;

  }


}

class ShowCourses {

  constructor(base_element) {
    this.container = document.getElementById(base_element);
  }

  fetchDataFromApi(){

    $.ajax({
      url: 'https://bsi-members.lndo.site/wp-admin/admin-ajax.php?action=dummy-data',
      type: 'GET',
      dataType: 'json'
    }).done((response)=>{

     this.handleSuccess(response);

    }).fail(this.handlerError);
  }


  handleSuccess(response){
      console.log(this)
    response.forEach((elem)=>{
      console.log(elem);
      let post_title = new PostTitle(elem.post_title);
      let post_description = new PostDescription(elem.post_content);
      this.container.innerHTML +=  `<div> ${post_title.render()} ${post_description.render()} </div>`;
    })


  }

  handlerError(){
    alert("ERRRO");
  }


}



let x = new ShowCourses("root");
console.log(x.fetchDataFromApi());

console.log(x);

