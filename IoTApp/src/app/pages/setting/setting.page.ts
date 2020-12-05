import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';
import { CustomService } from 'src/app/custom.service';
import { Storage } from '@ionic/storage';
import { HTTP } from '@ionic-native/http/ngx';

@Component({
  selector: 'app-setting',
  templateUrl: './setting.page.html',
  styleUrls: ['./setting.page.scss'],
})
export class SettingPage implements OnInit {
//tslint:disable
settings;
is_group = false;
  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController,
              private storage:Storage,
              private custom :CustomService,
              private http:HTTP,) {
    this.platform.backButton.subscribe(() => {
      this.go_back();
    });
  }
  // function to go back to prev page
  go_back() {
    this.router.navigateByUrl('/home');
  }
  
  Logout(){
      this.custom.showSuccessMessage("Success","Successfully logout");
      this.storage.clear();
      this.navCtrl.navigateRoot('welcome');
  }

  ngOnInit() {
this.getData();  
}
  
  getData(){
    this.storage.get('user').then((user:any)=>{
       this.http.get(this.custom.getApi()+'UserDeviceSettings',{get_settings:'true',api_key:this.custom.getApiKey(),u_id:user.id},{}).then((data:any)=>{
           console.log(data);
           let res = JSON.parse(data.data);
           console.log(res);
           if(res.type){
               this.settings  = res.data;
           }else{
               
           }
           this.is_group = res.is_added_group;
       }).catch((err:any)=>{
           console.log(err);
       })
    }); 

    
 }
  SaveSettings(field,event){
    console.log('field'+field);
    console.log(event);
    let value = event.detail.value;
    let vall;
    let val;
    if(value == 'on'){
       vall = event.detail.checked;
       if(vall){
                val=  "1";
       }else if(vall == false){
            val=  "0";
           
       }
    }else{
        val = value;
    }
    this.storage.get('user').then((user:any)=>{
        
        this.http.post(this.custom.getApi()+'UserDeviceSettings',{saveSettings:'true',api_key:this.custom.getApiKey(),
                                                                  user_id:user.id,
                                                                  field:field,
                                                                  value:val.toString()},{})
          .then((data:any)=>{
                   console.log(data);
                   let res = JSON.parse(data.data);
                   console.log(res);                                                   
          }).catch((err:any)=>{
             console.log(err); 
          });
      });
    
    
}

}
