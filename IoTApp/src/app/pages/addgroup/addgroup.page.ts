//tslint:disable
import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { HTTP } from '@ionic-native/http/ngx';
import { CustomService } from 'src/app/custom.service';
import { Storage } from '@ionic/storage';

@Component({
  selector: 'app-addgroup',
  templateUrl: './addgroup.page.html',
  styleUrls: ['./addgroup.page.scss'],
})
export class AddgroupPage implements OnInit {
    addGroupForm:FormGroup;
  items = Array();
  form_submit = false;
  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController,
              public formBuilder: FormBuilder,
              private http:HTTP,
              private custom:CustomService,
              private storage:Storage ) {
    this.platform.backButton.subscribe(() => {
      this.navCtrl.setDirection('back');
    });
  }
  // function to go back to prev page
  go_back() {
    this.router.navigateByUrl('/setting');
  }


  ngOnInit() {
   this.addGroupForm = this.formBuilder.group({
    nickname:['',Validators.compose([Validators.required])],
    address:['',Validators.compose([Validators.required])],
    city:['',Validators.compose([Validators.required])],
    state:['',Validators.compose([Validators.required])],
    country:['',Validators.compose([Validators.required])],
    zipcode:['',Validators.compose([Validators.required])],
   });
  }
  
  
  AddGroup(controls){
      console.log(controls);
      this.form_submit  =  true;
      
      if (!this.addGroupForm.invalid ) {
        this.storage.get('user').then((user:any)=>{
           this.http.post(this.custom.getApi()+'Group',{inserting:'true',
                                                        u_id:user.id,
                                                        nickname:controls.nickname.value,
                                                        address:controls.address.value,
                                                        city:controls.city.value,
                                                        state:controls.state.value,
                                                        country:controls.country.value,
                                                        zipcode:controls.zipcode.value,},{})
           .then((data:any)=>{
               console.log(data);
               let res = JSON.parse(data.data);
               console.log(res);
               if(res.type){
                  if(res.is_current_group){
                    this.storage.set('current_group', res.current_group_data);
                  }
                  this.custom.showSuccessMessage("Success","Successfully Inserted Device Group and Control");
                  this.go_back();
               }else{
                this.custom.showSuccessMessage("Error !","Error On Inserting Device Group");
                   
               }
           }) ;
        });
        
      }
  }

}
