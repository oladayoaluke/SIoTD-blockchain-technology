import { Component, OnInit, ViewChildren } from '@angular/core';

import { Platform, IonRouterOutlet, NavController } from '@ionic/angular';
import { SplashScreen } from '@ionic-native/splash-screen/ngx';
import { StatusBar } from '@ionic-native/status-bar/ngx';
import { Storage } from '@ionic/storage';

// import { faCoffee } from '@fortawesome/free-solid-svg-icons';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss']
})
export class AppComponent {
  subscription: any;
  @ViewChildren(IonRouterOutlet) routerOutlets: IonRouterOutlet;
  constructor(
    private platform: Platform,
    private splashScreen: SplashScreen,
    private statusBar: StatusBar,
    public navCtrl: NavController,
    private storage :Storage
  ) {
    this.initializeApp();
  }

  initializeApp() {
    this.platform.ready().then(() => {
      this.storage.get('user').then((user:any)=>{
        if(user != null){
          this.navCtrl.navigateRoot('home');
        }else{
          this.navCtrl.navigateRoot('welcome');

				}
        console.log(user);
      })
      this.statusBar.styleDefault();
      this.splashScreen.hide();
    });
  }
}
