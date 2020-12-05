import { Component } from '@angular/core';

@Component({
  selector: 'app-home',
  templateUrl: 'pages/home/home.page.html',
  styleUrls: ['pages/home/home.page.scss'],
})
export class HomePage {
//tslint:disable
  constructor() {}

  counter_num = 0;
  text = 'The world is your oyster.';

  onchangeText(){
    if(this.counter_num  == 0)
    {
      this.text = "Text is changed.";
      this.counter_num = 1;
    }
    else
    {
      this.text = "The world is your oyster.";
      this.counter_num = 0;
    }
  }
}
