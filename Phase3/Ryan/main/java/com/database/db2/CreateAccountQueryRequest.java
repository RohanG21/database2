package com.database.db2;

import android.util.Log;

import com.android.volley.AuthFailureError;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import java.util.HashMap;
import java.util.Map;

public class CreateAccountQueryRequest extends StringRequest {
    private Map<String, String> args;
    private static Response.ErrorListener err = new Response.ErrorListener(){
        @Override
        public void onErrorResponse(VolleyError error){
            Log.d("please","Error listener response: " + error.getMessage());
        }
    };

    public CreateAccountQueryRequest(String name, String studentID, String email, String password, String url, Response.Listener<String> listener){
        super(Method.POST, url, listener, err);
        args = new HashMap<String, String>();
        args.put("name", name);
        args.put("student_id", studentID);
        args.put("email", email);
        args.put("password", password);
    }

    @Override
    protected Map<String, String> getParams() throws AuthFailureError {
        return args;
    }
}