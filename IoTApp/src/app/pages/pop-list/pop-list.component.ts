import { Component, OnInit, ViewChild, ElementRef, EventEmitter, Output, NgModule, CUSTOM_ELEMENTS_SCHEMA  } from '@angular/core';
import { PopoverController, NavParams, Events, IonRadioGroup, NavController  } from '@ionic/angular';
import { OpenNativeSettings } from '@ionic-native/open-native-settings/ngx';
import { Storage } from '@ionic/storage';
import { HTTP } from '@ionic-native/http/ngx';
import { CustomService } from 'src/app/custom.service';


//tslint:disable
@Component({
  selector: 'app-pop-list',
  templateUrl: './pop-list.component.html',
  styleUrls: ['./pop-list.component.scss']
})
@NgModule({
  schemas: [
    CUSTOM_ELEMENTS_SCHEMA
  ]
})

export class PopListComponent implements OnInit {
  @ViewChild('iongroup', { static: true }) iongroup: IonRadioGroup;
  @Output() selectedRadioItem = new EventEmitter<string>();
  page;

  param: any;
  // Name of selected item
  currentDeviceName : string;
  currentDeviceID;
  
  // Get value on ionChange on IonRadioGroup
  selectedRadioGroup: any;
  // Get value on ionSelect on IonRadio item
  added_device;
  constructor(
    private events: Events,
    private navParams: NavParams,
    public navCtrl: NavController,
    public storage: Storage,
    private popoverController: PopoverController,
    private http:HTTP,
    private custom:CustomService,
    private event:Events ) {
  }

  ngOnInit() {
    // Get data from popover page
    this.page = this.navParams.data.paramID;
    this.iongroup.value = this.page;
    this.getData();
    
    this.storage.get('current_group').then((data:any)=>{
        console.log(data);
        this.currentDeviceID = data.gid;
    });

  }
  
  
  getData(){
      this.storage.get('user').then((user:any)=>{
          
      this.http.get(this.custom.getApi()+'AddingDevice',{getAddedGroup:'true',api_key:this.custom.getApiKey(),user_id:user.id},{}).then((data:any)=>{
          console.log(data);
          let res=JSON.parse(data.data);
          console.log(res);
          this.added_device = res;
          
          
      }).catch((err:any)=>{
          console.log(err);
      })
    });
      
  }

  async radioSelect(radioSelect) {
    this.storage.set('current_group', radioSelect);
    console.log(radioSelect);
    this.currentDeviceName = radioSelect.nickname;
    this.currentDeviceID = radioSelect.gid;
    this.event.publish('current_group',radioSelect);
    this.popoverController.dismiss();
  }

}
