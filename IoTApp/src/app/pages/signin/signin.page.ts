import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';
import { HTTP } from '@ionic-native/http/ngx';
import { CustomService } from 'src/app/custom.service';
import { Storage } from '@ionic/storage';

@Component({
  selector: 'app-signin',
  templateUrl: './signin.page.html',
  styleUrls: ['./signin.page.scss'],
})
export class SigninPage implements OnInit {
    // tslint:disable
    
  username;
  password;
  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController,
              public http :HTTP,
              public custom: CustomService,
              private storage: Storage,
              public navCont: NavController) {
    this.platform.backButton.subscribe(() => {
    this.go_back();
    this.handleUsernameValue();
    this.handlePassValue();
    });
  }
  // function to go back to prev page
  go_back() {
  this.router.navigateByUrl('/welcome');
  }

  SignIn(){
    console.log("CLICKED");
    console.log("API KEY : " + this.custom.getApiKey());


    this.http.post(this.custom.getApi()+'signin', {loginReq:true,
                                                  api_key:this.custom.getApiKey(),
                                                  username:this.username,
                                                  password:this.password}, {})
  .then((data:any)=>{
    if(data.data){
      console.log(data.data);
      let res = JSON.parse(data.data);
      if(res.type){
        this.custom.showSuccessMessage("Success",res.message);
        this.storage.set("user",res.data);
        this.navCont.navigateRoot('home');
        
      }else{
        this.custom.showSuccessMessage("ERROR !",res.message);
  
      }
      console.log(data);
    }else{
      this.custom.showSuccessMessage("ERROR !","Wrong Username or Password");
    }
    console.log("API KEY : " + this.custom.getApiKey());

  })
    

  .catch(error => {

   console.log(error);

  });
  }

  handleUsernameValue(){
    return "";
  }

  handlePassValue(){
    return "";
  }


  ngOnInit() {
  }

}
