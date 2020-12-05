import { Component, OnInit, HostListener  } from '@angular/core';
import { IonRouterOutlet, Platform, Events } from '@ionic/angular';
import { Router } from '@angular/router';
import { NavController, PopoverController } from '@ionic/angular';
import { PopListComponent } from '../../pages/pop-list/pop-list.component';
import { Storage } from '@ionic/storage';
import { CustomService } from 'src/app/custom.service';
import { HTTP } from '@ionic-native/http/ngx';
import { Socket } from 'ngx-socket-io';


@Component({
  selector: 'app-home',
  templateUrl: './home.page.html',
  styleUrls: ['./home.page.scss'],
})
export class HomePage implements OnInit {
//tslint:disable
  //currentDeviceName : string;
    t_switch_status;
    p_switch_status;
    isStart = false;
    isReset = false;
    currentGroup;
    currentSettings;

    message = {};
    messages = [];
  

    
  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController,
              public popoverController: PopoverController,
              private storage :Storage,
              private custom:CustomService,
              private http:HTTP,
              private event:Events,
              private socket: Socket) {
                this.storage.get('user').then((data:any)=>{
                  console.log("LETSEEE");
                  console.log(data);

                });
      this.platform.backButton.subscribe(() => {
        this.navCtrl.back();
    });
    
    this.event.subscribe('current_group', (data) =>{
       
        this.custom.presentLoading();

        this.getData();
        
        this.custom.hideLoading();
          
    });
  }
  
  go_back() {
    this.router.navigateByUrl('/welcome');
  }

  ngOnInit() {
    this.getData();

    this.socket.connect();
    
    this.socket.emit('set-name', "SIoTD");

    this.socket.fromEvent('message').subscribe(message => {
      this.messages.push(message);
    });

  }


  sendConfigurationMessage(message:any) {
    this.socket.emit('send-message', message);

    this.message = '';
  }

  ionViewWillLeave() {
    this.socket.disconnect();
  }

  getData(){
    
      this.storage.get('user').then((user:any)=>{
      this.storage.get('current_group').then((device:any)=>{
          
        this.http.get(this.custom.getApi()+'UserDeviceSettings',{GetHomeData:'true',
                                                      api_key:this.custom.getApiKey(),
                                                   uid:user.id,
                                                   gid:device.gid},
        {}).then((data:any)=>{
             console.log(data);
             let res = JSON.parse(data.data);
             console.log(res);
             this.currentGroup = res.currentGroup;
             this.currentSettings = res.settings;
             if(this.currentSettings != undefined && this.currentSettings != null){
                 if(this.currentSettings.start_status == 1 ||this.currentSettings.start_status == '1' ){
                    this.isStart =  true;
                    this.isReset = false;  
                 }else{
                     this.isStart = false
                     this.isReset = true;
                 }
                 
                 if(this.currentSettings.t_switch_status == 1 ||this.currentSettings.t_switch_status == '1' ){
                    this.t_switch_status =  true;  
                 }else{
                     this.t_switch_status = false
                 }
                 
                 if(this.currentSettings.p_switch_status == 1 ||this.currentSettings.p_switch_status == '1' ){
                    this.p_switch_status =  true;  
                 }else{
                     this.p_switch_status = false
                 }
            }
             
             
        });
        
      });
          
      })
  }
  currentpopover = null;

  async settingsPopover(ev: any) {
    const popover = await this.popoverController.create({
      component: PopListComponent,
      event: ev,
      translucent: true,
      // componentProps: { page: 'Login' },
      cssClass: 'popover_class',
    });
    this.currentpopover = popover;
    // /** Sync event from popover component */
    // this.events.subscribe('fromPopoverEvent', () => {
    //   this.syncTasks();
    // });
    return await popover.present();
  }
  
  StartToggle(field,ev)
  {
      console.log(ev);
      let val;
      if(field == 'start_status'){      
        if(!this.isStart){
          this.isStart = true;
          this.isReset = false;

          let tokenStr = "a460201b60201c565b6500039b565b60";
          let in_blockchain_account = "0xbfE0B09fbdB2610944781752e698504fD023785F";
          let nickname = "SIoTD_E";
          let ticket = "19a97d3741a7861fbdbb21f21869368f";
          let gid  = 110;
          let outMessage = { token: tokenStr, blockchain_account: in_blockchain_account, nickname:nickname,uid:5, gid:gid,ticket: ticket, role:0,adid:5, dtid:1};
          this.sendConfigurationMessage(outMessage);
          val = 1;
            
          }
      }
    else if(field == 't_switch_status')//RESET
    {
      
        let outMessage = { cmd: "RESET WILL BE DONE WITH BLOCKCHAIN OBJECT! NOT YET IMPLEMENTED"};
        
        let vall =  ev.detail.value;   
        if(vall == 'on'){
            let valll = ev.detail.checked;
            if(valll){
                val = 1;
                this.sendConfigurationMessage(outMessage);
            }else{
                val = 0;
            }
        }

    }
    else if(field == 'p_switch_status')//LED OFF
    {
        let outMessage1 = { cmd: "TURN LED ON WILL BE DONE THROUGH BLOCKCHAIN OBJECT"};
        let outMessage2 = { cmd: "TURN LED OFF WILL BE DONE THROUGH BLOCKCHAIN OBJECT"};
        

        let vall =  ev.detail.value;   
        if(vall == 'on'){
            let valll = ev.detail.checked;
            if(valll){
                val = 1;
                this.sendConfigurationMessage(outMessage1);
            }else{
                val = 0;
                this.sendConfigurationMessage(outMessage2);
            }
        }
        //Send command to blockchain using adid(5) as receiver
    }
  
    console.log(field);
    console.log(val);
      

      this.storage.get('user').then((user:any)=>{
        this.http.post(this.custom.getApi()+'UserDeviceSettings',{SetHomeData:'true',api_key:this.custom.getApiKey(),
                                                            uid:user.id,
                                                            field:field,
                                                            value:val},
        {}).then((data:any)=>{
            console.log(data);
            let res = JSON.parse(data.data);
            console.log(res);
            
        }); 
    });


  }
}
