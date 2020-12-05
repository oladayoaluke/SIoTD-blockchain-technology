import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';

@Component({
  selector: 'app-privacy',
  templateUrl: './privacy.page.html',
  styleUrls: ['./privacy.page.scss'],
})
export class PrivacyPage implements OnInit {

  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController) {
    this.platform.backButton.subscribe(() => {
    this.go_back();
    });
  }
  // function to go back to prev page
  go_back() {
  this.router.navigateByUrl('/welcome');
  }


  ngOnInit() {
  }

}
