import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PopListComponent } from './pop-list.component';

describe('PopListComponent', () => {
  let component: PopListComponent;
  let fixture: ComponentFixture<PopListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PopListComponent ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PopListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
