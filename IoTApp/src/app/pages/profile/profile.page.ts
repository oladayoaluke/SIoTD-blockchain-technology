//tslint:disable

import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router, NavigationExtras } from '@angular/router';
import { Storage } from '@ionic/storage';
import { CustomService } from 'src/app/custom.service';
import { HTTP } from '@ionic-native/http/ngx';
@Component({
  selector: 'app-profile',
  templateUrl: './profile.page.html',
  styleUrls: ['./profile.page.scss'],
})
export class ProfilePage implements OnInit {
    userdata;
  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController, 
              private storage:Storage,
              private custom:CustomService,
              private http:HTTP)  {
    this.platform.backButton.subscribe(() => {
    this.go_back();
    this.profileBtnClick();
    });
  }
  // function to go back to prev page
  go_back() {
  this.router.navigateByUrl('/setting');
  }

  profileBtnClick() {
      
    let navigationExtras: NavigationExtras = {
        queryParams: {
            userdata: JSON.stringify(this.userdata),
        }
    };
    this.navCtrl.navigateForward(['/profile-update'],navigationExtras);
  }


  ngOnInit() {
      this.getData();
  }
  ionViewWillEnter(){
    this.getData();
      
  }
  
  getData(){
      this.storage.get('user').then((user:any)=>{
           this.http.get(this.custom.getApi()+'ProfileSetting',{fetch_detail:'true',api_key:this.custom.getApiKey(),u_id:user.id},{}).then((data:any)=>{
               console.log(data);
               let res = JSON.parse(data.data);
               console.log(res); 
               this.userdata = res;
           }); 
      });
  }

}
