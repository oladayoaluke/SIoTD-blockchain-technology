import { NgModule } from '@angular/core';
import { BrowserModule, HAMMER_GESTURE_CONFIG } from '@angular/platform-browser';
import { RouteReuseStrategy } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';

import { IonicModule, IonicRouteStrategy } from '@ionic/angular';
import { SplashScreen } from '@ionic-native/splash-screen/ngx';
import { StatusBar } from '@ionic-native/status-bar/ngx';
import { Geolocation } from '@ionic-native/geolocation/ngx';
import { NativeGeocoder } from '@ionic-native/native-geocoder/ngx';
import { IonicStorageModule } from '@ionic/storage';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
// import { AngularFontAwesomeModule } from 'angular-font-awesome';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';
import { PopListComponent } from './pages/pop-list/pop-list.component';
import { HTTP } from '@ionic-native/http/ngx';
import { CustomService } from './custom.service';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { IonicGestureConfig } from './HammerGestureConfig';
import { SocketIoModule, SocketIoConfig } from 'ngx-socket-io';
const config1: SocketIoConfig = { url: 'http://localhost:65430', options: {} };




@NgModule({
  declarations: [AppComponent, PopListComponent],
  entryComponents: [PopListComponent],

  imports: [
    BrowserModule,
    IonicModule.forRoot(),
    IonicStorageModule.forRoot(),
    HttpClientModule,
    AppRoutingModule,
    FontAwesomeModule,
    FormsModule,
    ReactiveFormsModule,
    SocketIoModule.forRoot(config1),
  ],
  providers: [
    Geolocation,
    NativeGeocoder,
    StatusBar,
    SplashScreen,
    { provide: RouteReuseStrategy, useClass: IonicRouteStrategy },
    HTTP,
    CustomService,
        {
            provide: HAMMER_GESTURE_CONFIG,
            useClass: IonicGestureConfig
        },
  ],
  bootstrap: [AppComponent]
})
export class AppModule {}
