package com.database.db2;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class InstructorMain extends AppCompatActivity {
    Button courseRecordBtn;
    Button returnLogin;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.instructormain);

        TextView welcomeName = (TextView) findViewById(R.id.welcomeName);
        Intent intent = getIntent();
        final String email = intent.getStringExtra("email");
        final String password = intent.getStringExtra("password");
        final String name = intent.getStringExtra("name");
        final String inid = intent.getStringExtra("inid");
        String welcome = "Welcome " + name;
        welcomeName.setText(welcome);


        courseRecordBtn = (Button) findViewById(R.id.course_record_btn);
        returnLogin = (Button) findViewById(R.id.login_btn);

        courseRecordBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent instructorRecordIntent = new Intent(InstructorMain.this, InstructorRecord.class);
                instructorRecordIntent.putExtra("email", email);
                instructorRecordIntent.putExtra("password", password);
                instructorRecordIntent.putExtra("name", name);
                instructorRecordIntent.putExtra("inid",inid);
                InstructorMain.this.startActivity(instructorRecordIntent);
            }
        });
        returnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent loginIntent = new Intent(InstructorMain.this, Login.class);
                InstructorMain.this.startActivity(loginIntent);
            }
        });
    }
}