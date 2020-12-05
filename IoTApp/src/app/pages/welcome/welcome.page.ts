import { Component, OnInit, ViewChildren, QueryList  } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';

@Component({
  selector: 'app-welcome',
  templateUrl: './welcome.page.html',
  styleUrls: ['./welcome.page.scss'],
})
export class WelcomePage implements OnInit {
  subscription: any;
  @ViewChildren(IonRouterOutlet) routerOutlets: IonRouterOutlet;

  constructor(public platform: Platform,
              private router: Router,
              public navCtrl: NavController) {
      this.ionViewDidEnter();
  }
  ionViewDidEnter() {

    this.subscription = this.platform.backButton.subscribe(() => {
        navigator['app'].exitApp();
    });
  }

  ionViewWillLeave() {
      this.subscription.unsubscribe();
  }
  ngOnInit() {
  }

}
