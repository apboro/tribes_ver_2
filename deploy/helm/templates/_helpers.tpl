{{- define "..names.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{- define "..names.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{- define "..names.fullname" -}}
{{- if .Values.fullnameOverride -}}
{{- .Values.fullnameOverride | trunc 63 | trimSuffix "-" -}}
{{- else -}}
{{- $name := default .Chart.Name .Values.nameOverride -}}
{{- if contains $name .Release.Name -}}
{{- .Release.Name | trunc 63 | trimSuffix "-" -}}
{{- else -}}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" -}}
{{- end -}}
{{- end -}}
{{- end -}}

{{- define "..labels.standard" -}}
app.kubernetes.io/name: {{ include "..names.name" . }}
helm.sh/chart: {{ include "..names.chart" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end -}}

{{- define "..labels.matchLabels" -}}
app.kubernetes.io/name: {{ include "..names.name" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end -}}


{{- define "..imagePullSecret" }}
{{- $auth := printf "%s:%s" .credentials.username .credentials.password | b64enc -}}
{{- printf "{\"auths\": {\"%s\": {\"auth\": \"%s\"}}}" .registry $auth | b64enc }}
{{- end }}

{{- define "..imageName" }}
{{- printf "%s/%s:%s" .registry .repository .tag }}
{{- end }}


{{- define "..tplvalues.render" -}}
    {{- if typeIs "string" .value }}
        {{- tpl .value .context }}
    {{- else }}
        {{- tpl (.value | toYaml) .context }}
    {{- end }}
{{- end -}}
